function genRandKey() {
  if (document.getElementById('cipherselector').value == 'substitution') {
    var alphabet = "abcdefghijklmnopqrstuvwxyz";
    var shuffled = alphabet.split('').sort(function(){return 0.5-Math.random()}).join('');
    document.getElementById('key').value = shuffled;
  } else if (document.getElementById('cipherselector').value == 'rc4') {
    var alphabet = "0123456789abcdef";
    var shuffled = alphabet.split('').sort(function(){return 0.5-Math.random()}).join('');
    document.getElementById('key').value = shuffled;
  }
}