const userBtn = document.querySelector('#user-btn')
userBtn.addEventListener('click', function(){
const userBox = document.querySelector('.profile');
userBox.classList.toggle('active');
})

const searchForm = document.querySelector('.header .flex .search-form')

document.querySelector('#search-btn').onclick = () =>{
	searchForm.classList.toggle('active');
	profile.classList.remove('active');
}


const toggle = document.querySelector('#menu-btn');
toggle.addEventListener('click', function() {

	const navbar = document.querySelector('.navbar');
	navbar.classList.toggle('active');
})



