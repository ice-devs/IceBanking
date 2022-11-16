function w3_open() {
    document.getElementById("mainsy").style.marginLeft = "40%";
    document.getElementById("mySidebar").style.width = "40%";
    document.getElementById("mySidebar").style.display = "block";
    document.getElementById("openNav").style.display = 'none';
  }
  
  function w3_close() {
    document.getElementById("mainsy").style.marginLeft = "0%";
    document.getElementById("mySidebar").style.display = "none";
    document.getElementById("openNav").style.display = "inline-block";
  }