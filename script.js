let nav = document.querySelector(".navbar");
let closenav = document.querySelector(".cross-btn");
const activenav = () => {
  nav.classList.add("active");
  closenav.classList.add("active");
}

const removenav = () => {
  nav.classList.remove("active");
  closenav.classList.remove("active");
}

document.querySelector("#menu-btn").addEventListener("click",activenav);
window.addEventListener("scroll",removenav);
closenav.addEventListener("click",removenav);