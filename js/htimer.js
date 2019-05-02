var id = setInterval(frame, 1000);
var bool = true;
var elem = document.getElementById("blink");

function frame() {
  if (bool) {
    elem.style.display = "inline";
    bool = false;
  } else {
    elem.style.display = "none";
    bool = true;
  }
}