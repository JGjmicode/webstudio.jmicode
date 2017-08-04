
actionsTableProjects();

function actionsTableProjects() {
    var projectTableRow = document.querySelectorAll(".table-projects>tbody>tr");
    var ticketTableRow = document.querySelectorAll(".table-tickets>tbody>tr");
    for (var i = 0; i < projectTableRow.length; i++) {
        projectTableRow[i].addEventListener("click", function(){
            window.location.href = "/projects/view?id="+this.getAttribute("data-key");
        });
    }
    for (var i = 0; i < ticketTableRow.length; i++) {
        ticketTableRow[i].addEventListener("click", function(){
            window.location.href = "/tikets/view?id="+this.getAttribute("data-key");
        });
    }
}


