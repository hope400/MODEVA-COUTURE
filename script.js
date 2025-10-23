// header show/hide logic for search and profile (hover-friendly)
document.addEventListener('DOMContentLoaded', function () {
  const searchBtn = document.getElementById('search-btn');
  const searchForm = document.getElementById('header-search-form');

  const userBtn = document.getElementById('user-btn');
  const profileBox = document.getElementById('header-profile-box');

  let searchHideTimer = null;
  let profileHideTimer = null;

  // Show search when hovering icon or form
  function showSearch() {
    clearTimeout(searchHideTimer);
    searchForm.style.display = 'flex';
  }
  function hideSearchDeferred() {
    clearTimeout(searchHideTimer);
    searchHideTimer = setTimeout(() => searchForm.style.display = 'none', 200);
  }

  // If you want clicking outside to close them:
  document.addEventListener('click', (e) => {
    if (!searchForm.contains(e.target) && !searchBtn.contains(e.target)) {
      searchForm.style.display = 'none';
    }
    if (!profileBox.contains(e.target) && !userBtn.contains(e.target)) {
      profileBox.style.display = 'none';
    }
  });

  // Toggle search form on hover
const searchBtn = document.querySelector('#search-btn');
const searchForm = document.querySelector('.search-form');
const userBtn = document.querySelector('#user-btn');
const profileBox = document.querySelector('.profile');

searchBtn.addEventListener('mouseenter', () => searchForm.style.display = 'flex');
searchForm.addEventListener('mouseleave', () => searchForm.style.display = 'none');

userBtn.addEventListener('mouseenter', () => profileBox.style.display = 'block');
profileBox.addEventListener('mouseleave', () => profileBox.style.display = 'none');

});


<script>
function validateSearch() {
  const input = document.getElementById('searchInput').value.trim();
  if (input === "") {
    alert("Please enter something to search.");
    return false;
  }
  return true;
}
</script>

