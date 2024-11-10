console.log("JavaScript file loaded");
// khung search
document.addEventListener("DOMContentLoaded", function () {
  const searchToggle = document.getElementById("searchToggle");
  const searchContainer = document.getElementById("searchContainer");
  const searchForm = document.getElementById("searchForm");
  const searchBox = document.getElementById("searchBox");

  document.addEventListener("click", function (event) {
    if (
      !searchContainer.contains(event.target) &&
      !searchToggle.contains(event.target)
    ) {
      searchContainer.classList.remove("active");
    }
  });

  searchToggle.addEventListener("click", function (e) {
    e.preventDefault();
    searchContainer.classList.toggle("active");
    if (searchContainer.classList.contains("active")) {
      searchBox.focus();
    }
  });

  searchForm.addEventListener("submit", function (e) {
    if (searchBox.value.trim() === "") {
      e.preventDefault();
      alert("Vui lòng nhập từ khóa tìm kiếm");
    }
  });
});
