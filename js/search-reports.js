// assets/search-reports.js

function filterTable() {
    var input, filter, table, tr, td, i, j, txtValue;
    input = document.getElementById("searchBar");
    filter = input.value.toUpperCase();
    table = document.getElementById("reportTable");
    tr = table.getElementsByTagName("tr");
    
    for (i = 1; i < tr.length; i++) {
        tr[i].style.display = "none";
        td = tr[i].getElementsByTagName("td");
        for (j = 0; j < td.length; j++) {
            if (td[j]) {
                txtValue = td[j].textContent || td[j].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                    break;
                }
            }
        }
    }
}

function sortTable(n) {
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById("reportTable");
    switching = true;
    dir = "asc"; 
    while (switching) {
        switching = false;
        rows = table.rows;
        for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;
            x = rows[i].getElementsByTagName("TD")[n];
            y = rows[i + 1].getElementsByTagName("TD")[n];
            if (dir == "asc") {
                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                }
            } else if (dir == "desc") {
                if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                }
            }
        }
        if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            switchcount ++;      
        } else {
            if (switchcount == 0 && dir == "asc") {
                dir = "desc";
                switching = true;
            }
        }
    }
}
function viewReport(reportId) {
    // Make an AJAX request to fetch the report details
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'php/get_report_details.php?id=' + reportId, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var report = JSON.parse(xhr.responseText);
            // Fill the pop-up with report details
            document.getElementById('popupStudentName').textContent = report.student_name;
            document.getElementById('popupViolation').textContent = report.violation;
            document.getElementById('popupOffenses').textContent = report.no_of_offense;
            document.getElementById('popupDetailedReport').textContent = report.detailed_report;
            document.getElementById('popupDate').textContent = report.date_of_violation;
            document.getElementById('popupActionTaken').textContent = report.action_taken;
            document.getElementById('popupCreatedBy').textContent = report.created_by;
            // Show the pop-up
            document.getElementById('reportPopup').style.display = 'block';
        }
    };
    xhr.send();
}

function closePopup() {
    document.getElementById('reportPopup').style.display = 'none';
}