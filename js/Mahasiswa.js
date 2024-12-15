class Mahasiswa {
    constructor(searchInputId, internshipContainerClass) {
      this.searchInput = document.getElementById(searchInputId);
      this.internshipContainer = document.querySelector(`.${internshipContainerClass}`);
      this.internship = Array.from(this.internshipContainer.getElementsByClassName("jList")); 
      this.init();
    }
  
    init() {
      this.searchInput.addEventListener("input", () => this.filterBidang());
      this.klikMagang();
    }
  
    filterBidang() {
      const query = this.searchInput.value.toLowerCase();
      this.internship.forEach((job) => {
        const jobDescription = job.querySelector("p").textContent.toLowerCase(); 
        if (jobDescription.includes(query)) {
          job.style.display = "grid";
        } else {
          job.style.display = "none";
        }
      });
    }
    
    klikMagang(){
      this.internship.forEach((internship)=>{
        internship.addEventListener("click", () => {
          window.location.href="../html/magang.php";
        });
      });
    }
    }
  
  document.addEventListener("DOMContentLoaded", () => {
    new Mahasiswa("searchBar", "internship-container");
  });

 
  