console.log("JavaScript file loaded");
// khung search
document.addEventListener("DOMContentLoaded", function () {
  const searchToggle = document.getElementById("searchToggle");
  const searchContainer = document.getElementById("searchContainer");
  const searchForm = document.getElementById("searchForm");
  const searchBox = document.getElementById("searchBox");
  //   const searchForm = document.getElementById("searchForm");
  //   const searchBox = document.getElementById("searchBox");

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
    // if (searchContainer.classList.contains("active")) {
    //   searchBox.focus();
    // }
  });
  // Đóng ô tìm kiếm khi click bên ngoài
  document.addEventListener("click", function (e) {
    if (
      !searchContainer.contains(e.target) &&
      !searchToggle.contains(e.target)
    ) {
      searchContainer.classList.remove("active");
    }
  });
  ///////////////////
  searchForm.addEventListener("submit", function (e) {
    if (searchBox.value.trim() === "") {
      e.preventDefault();
      alert("Vui lòng nhập từ khóa tìm kiếm");
    }
    searchBox.addEventListener("keypress", function (e) {
      if (e.key === "Enter") {
        e.preventDefault(); // Ngăn chặn hành vi mặc định của form
        searchForm.submit(); // Submit form
      }
    });
    document.addEventListener("click", function (e) {
      if (
        !searchContainer.contains(e.target) &&
        !searchToggle.contains(e.target)
      ) {
        searchContainer.classList.remove("active");
      }
    });
  });
});
