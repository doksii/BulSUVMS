// assets/search-students.js

function filterTable() {
    var input, filter, table, tr, td, i, j, txtValue;
    input = document.getElementById("searchBar");
    filter = input.value.toUpperCase();
    table = document.getElementById("studentTable");
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
    table = document.getElementById("studentTable");
    switching = true;
    dir = "asc"; 
    while (switching) {
        switching = false;
        rows = table.rows;
        for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;
            x = rows[i].getElementsByTagName("TD")[n];
            y = rows[i+1].getElementsByTagName("TD")[n];
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
function viewStudent(studentNumber) {
    // Fetch student and report information using AJAX
    fetch(`php/fetch_student_info.php?student_number=${studentNumber}`)
        .then(response => response.json())
        .then(data => {
            // Populate the modal with student information
            const studentInfo = `
                <p><strong>Student Name:</strong>${data.student.name}</p>
                <p><strong>Student Number:</strong> ${data.student.student_number}</p>
                <p><strong>Gender:</strong> ${data.student.gender}</p>
                <p><strong>Department:</strong> ${data.student.department}</p>
            `;
            document.getElementById('studentInfo').innerHTML = studentInfo;

            // Populate the modal with reports
            let reportsHTML = '<table><tr><th>Violation</th><th>No of Offenses</th><th>Detailed Report</th><th>Date of Violation</th><th>Action Taken</th></tr>';
            data.reports.forEach(report => {
                reportsHTML += `<tr><td>${report.violation}</td><td>${report.no_of_offense}</td><td>${report.detailed_report}</td><td>${report.date_of_violation}</td><td>${report.action_taken}</td></tr>`;
            });
            reportsHTML += '</table>';
            document.getElementById('reportsTable').innerHTML = reportsHTML;

            // Show the modal
            document.getElementById('studentModal').style.display = 'block';
        })
        .catch(error => console.error('Error:', error));
}
function closeModal() {
    document.getElementById('studentModal').style.display = 'none';
}

function filterReports() {
    const input = document.getElementById("searchReports");
    const filter = input.value.toLowerCase();
    const table = document.querySelector("#reportsTable table");
    const tr = table.getElementsByTagName("tr");

    for (let i = 1; i < tr.length; i++) {
        const tdArray = tr[i].getElementsByTagName("td");
        let found = false;
        for (let j = 0; j < tdArray.length; j++) {
            if (tdArray[j]) {
                if (tdArray[j].innerHTML.toLowerCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }
        }
        tr[i].style.display = found ? "" : "none";
    }
}
