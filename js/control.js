function genRandKey() {
  if (document.getElementById('ciphertype').value == 'substitution') {
    var alphabet = "abcdefghijklmnopqrstuvwxyz";
    var shuffled = alphabet.split('').sort(function(){return 0.5-Math.random()}).join('');
    document.getElementById('key').value = shuffled;
  } else if (document.getElementById('ciphertype').value == 'RC4') {
    var alphabet = "0123456789abcdef";
    var shuffled = alphabet.split('').sort(function(){return 0.5-Math.random()}).join('');
    document.getElementById('key').value = shuffled;
  }
}