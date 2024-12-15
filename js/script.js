const container = document.getElementById("container");
const registerToggle = document.getElementById("register-toggle");
const loginToggle = document.getElementById("login-toggle");

registerToggle.addEventListener("click", () => {
  container.classList.add("active");
});

loginToggle.addEventListener("click", () => {
  container.classList.remove("active");
});

