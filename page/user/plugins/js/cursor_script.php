<script type="text/javascript">
    // AJAX IN PROGRESS GLOBAL VARS
    var search_accounts_ajax_in_progress = false;

    // DOMContentLoaded function
	document.addEventListener("DOMContentLoaded", () => {
        search_accounts(1);
    });

    // Table Responsive Scroll Event for Load More
    document.getElementById("accounts_table_res").addEventListener("scroll", () => {
        let total = sessionStorage.getItem('count_rows');
        let table_rows = parseInt(document.getElementById("list_of_accounts").childNodes.length);
        
        var scrollTop = document.getElementById("accounts_table_res").scrollTop;
        var scrollHeight = document.getElementById("accounts_table_res").scrollHeight;
        var offsetHeight = document.getElementById("accounts_table_res").offsetHeight;

        if (search_accounts_ajax_in_progress == false) {
            // check if the scroll reached the bottom
            if ((offsetHeight + scrollTop + 1) >= scrollHeight) {
                if (total > table_rows) {
                    search_accounts(2);
                }
            }
        }
    });

    const count_account_list = () => {
        let employee_no = sessionStorage.getItem('employee_no_search');
        let full_name = sessionStorage.getItem('full_name_search');
        let user_type = sessionStorage.getItem('user_type_search');

        let xhr = new XMLHttpRequest();
        let url = "../../process/user/pagination/cursor_p.php", type = "POST";
        var data = serialize({
            method: 'count_account_list',
            employee_no: employee_no,
            full_name: full_name,
            user_type: user_type
        });
        xhr.onreadystatechange = () => {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                let response = xhr.responseText;
                if (xhr.readyState == 4 && (xhr.status >= 200 && xhr.status < 400)) {
                    sessionStorage.setItem('count_rows', response);
                    var total_rows = parseInt(response);
                    let table_rows = parseInt(document.getElementById("list_of_accounts").childNodes.length);
                    let loader_count = document.getElementById("loader_count").value;
                    let counter_view = "";
                    let counter_view_search = "";

                    if (total_rows == 0) {
                        document.getElementById("counter_view_search").style.display = 'none';
                        document.getElementById("counter_view").style.display = 'none';
                        document.getElementById("load_more_data").style.display = 'none';
                    } else {
                        if (total_rows < 2) {
                            counter_view_search = `${total_rows} record found`;
                            counter_view = `${table_rows} row of ${total_rows} record`;
                        } else {
                            counter_view_search = `${total_rows} records found`;
                            counter_view = `${table_rows} rows of ${total_rows} records`;
                        }
                        document.getElementById("counter_view_search").innerHTML = counter_view_search;
                        document.getElementById("counter_view_search").style.display = 'block';
                        document.getElementById("counter_view").innerHTML = counter_view;
                        document.getElementById("counter_view").style.display = 'block';
                            
                        if (total_rows > loader_count) {
                            document.getElementById("load_more_data").style.display = 'block';
                        } else if (total_rows <= loader_count) {
                            document.getElementById("load_more_data").style.display = 'none';
                        }
                    }
                } else {
                    console.log(`System Error: Call IT Personnel Immediately!!! They will fix it right away. Error: url: ${url}, method: ${type} ( HTTP ${xhr.status} - ${xhr.statusText} ) Press F12 to see Console Log for more info.`);
                }
            }
        };
        xhr.open(type, url, true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send(data);
    }

    const search_accounts = option => {
        // If an AJAX call is already in progress, return immediately
        if (search_accounts_ajax_in_progress) {
            return;
        }

        let id = 0;
        let employee_no = document.getElementById('employee_no_search').value;
        let full_name = document.getElementById('full_name_search').value;
        let user_type = document.getElementById('user_type_search').value;
        let loader_count = 0;

        switch (option) {
            case 1:
                sessionStorage.setItem('employee_no_search', employee_no);
                sessionStorage.setItem('full_name_search', full_name);
                sessionStorage.setItem('user_type_search', user_type);
                break;
            case 2:
                id = document.getElementById("list_of_accounts").lastChild.getAttribute("user-account-id");
                employee_no = sessionStorage.getItem('employee_no_search');
                full_name = sessionStorage.getItem('full_name_search');
                user_type = sessionStorage.getItem('user_type_search');
                loader_count = parseInt(document.getElementById("loader_count").value);
                break;
            case 3:
                employee_no = sessionStorage.getItem('employee_no_search');
                full_name = sessionStorage.getItem('full_name_search');
                user_type = sessionStorage.getItem('user_type_search');
                break;
            default:
        }

        // Set the flag to true as we're starting an AJAX call
        search_accounts_ajax_in_progress = true;

        let xhr = new XMLHttpRequest();
        let url = "../../process/user/pagination/cursor_p.php", type = "POST";
        var data = serialize({
            method: 'search_account_list',
            id: id,
            employee_no: employee_no,
            full_name: full_name,
            user_type: user_type,
            c: loader_count
        });

        var loading = `<tr id="loading"><td colspan="6" style="text-align:center;"><div class="spinner-border text-dark" role="status"><span class="sr-only">Loading...</span></div></td></tr>`;

        switch (option) {
            case 1:
            case 3:
                document.getElementById("list_of_accounts").innerHTML = loading;
                break;
            case 2:
                document.getElementById("load_more_data").setAttribute('disabled', true);
                document.getElementById("list_of_accounts").insertAdjacentHTML('beforeend', loading);
                break;
            default:
        }

        xhr.onreadystatechange = () => {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                let response = xhr.responseText;
                if (xhr.readyState == 4 && (xhr.status >= 200 && xhr.status < 400)) {
                    switch (option) {
                        case 1:
                        case 3:
                            document.getElementById("list_of_accounts").innerHTML = response;
                            document.getElementById("loader_count").value = loader_count + 10;
                            break;
                        case 2:
                            document.getElementById("loading").remove();
                            document.getElementById("load_more_data").removeAttribute('disabled');
                            document.getElementById("list_of_accounts").insertAdjacentHTML('beforeend', response);
                            document.getElementById("loader_count").value = loader_count += 10;
                            break;
                        default:
                    }

                    count_account_list();
                } else {
                    document.getElementById("loading").remove();
                    document.getElementById("load_more_data").removeAttribute('disabled');
                    console.log(`System Error: Call IT Personnel Immediately!!! They will fix it right away. Error: url: ${url}, method: ${type} ( HTTP ${xhr.status} - ${xhr.statusText} ) Press F12 to see Console Log for more info.`);
                }

                // Set the flag back to false as the AJAX call has completed
                search_accounts_ajax_in_progress = false;
            }
        };
        xhr.open(type, url, true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send(data);
    }
</script>