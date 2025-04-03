let sidebar = document.querySelector(".sidebar");
let sidebarBtn = document.querySelector(".bx-menu");
// console.log(sidebarBtn);
sidebarBtn.addEventListener("click", () => {
  sidebar.classList.toggle("close");
});

let arrow = document.querySelectorAll(".arrow");

for (var i = 0; i < arrow.length; i++) {
  arrow[i].addEventListener("click", (e) => {
    // Loop through all arrows to remove 'showMenu' class from other parents
    for (var j = 0; j < arrow.length; j++) {
      let otherArrowParent = arrow[j].parentElement.parentElement;
      if (otherArrowParent !== e.target.parentElement.parentElement) {
        otherArrowParent.classList.remove("showMenu");
      }
    }
    // Toggle 'showMenu' class on the clicked arrow's parent
    let arrowParent = e.target.parentElement.parentElement;
    arrowParent.classList.toggle("showMenu");
  });
}


// --------------------------------------

// Get the modal
var popup = document.getElementById("popup");

// Get all the links that open the modal
var links = document.querySelectorAll(".action-view");

// Get the <span> element that closes the modal
var closeButton = document.querySelector(".close-button");

let openModal = (id)=> {
  window.location.href = '?edit_id=' + id;
  window.preventDefault();
};

// When the user clicks on a link, open the modal
links.forEach(function(link) {
  link.addEventListener("click", function(event) {
    event.preventDefault();
    popup.style.display = "block";
  });
});

// When the user clicks on <span> (x), close the modal
closeButton.onclick = function() {
  popup.style.display = "none";
}

// click anywhere outside the modal to close it
window.onclick = function(event) {
  if (event.target === popup) {
    popup.style.display = "none";
  }
}
