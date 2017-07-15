
actionsTableProjects();

function actionsTableProjects() {
    var projectTableRow = document.querySelectorAll(".table-projects>tbody>tr");
    for (var i = 0; i < projectTableRow.length; i++) {
        projectTableRow[i].addEventListener("click", function(){
            window.location.href = "/projects/view?id="+this.getAttribute("data-key");
        });
    }
}


