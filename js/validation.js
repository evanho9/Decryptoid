
//Client-side validation

function validateLogin(form) {
  fail = validateUsername(form.username.value)
  fail += validatePassword(form.password.value)
  if (fail == "") return true
  else { alert(fail); return false }
}

function validateRegistration(form) {
  fail = validateEmail(form.email.value)
  fail += validateUsername(form.username.value)
  fail += validatePassword(form.password.value)
  if (fail == "") return true
  else { alert(fail); return false }
}

function validateUsername(field) {
  minCharacters = 5
  if (field.replace(/\s/g, "") == "") return "No Username was entered.\n"
  else if (field.length < minCharacters)
    return "Usernames must be at least " + minCharacters + " characters.\n"
  else if (/[^a-zA-Z0-9_-]/.test(field))
    return "Only a-z, A-Z, 0-9, - and _ allowed in Usernames.\n"
  return ""
}

function validatePassword(field){
  minCharacters = 6
  if (field.replace(/\s/g, "") == "") return "No Password was entered.\n"
  else if (field.length < 6)
    return "Passwords must be at least " + minCharacters + " characters.\n"
  else if (!/[a-z]/.test(field) || ! /[A-Z]/.test(field) ||!/[0-9]/.test(field))
    return "Passwords require one each of a-z, A-Z and 0-9.\n"
  return ""
}

function validateEmail(field) {
  field = field + "";
  if (field.replace(/\s/g, "") == "") return "No Email was entered.\n"
  else if (!(/\S+@\S+\.\S+/.test(field)))
     return "The Email address is invalid.\n"
  return ""
}

function validateCrypto(form) {
  fail = ""
  if (form.ciphertype.value == "substitution")
    fail += validateSubKey(form.key.value)
  if (form.ciphertype.value == "double transposition") {
    fail += validateRowPerm(form.rowperm.value)
    fail += validateColPerm(form.colperm.value)
  }
  if (form.ciphertype.value == "RC4") {
    
  }
  if (fail == "") return true
  else { alert(fail); return false }
}

function validateSubKey(field) {
  numCharacters = 26
  if (field.replace(/\s/g, "") == "") 
    return "No key was entered.\n"
  else if (field.length != numCharacters)
    return "Alphabet must be " + numCharacters + " characters.\n"
  else if (/[^a-zA-Z0-9_-]/.test(field))
    return "Only a-z, A-Z, 0-9, - and _ allowed in alphabet.\n"
  return ""
}

function validateRowPerm(field) {
  field = field + "";
  if (field.replace(/\s/g, "") == "") return "No row permutation was entered.\n"
  else if (!(/[0-9]+(,[0-9]+)*/.test(field)))
     return "The row permutation is invalid.\n"
  return ""
}

function validateColPerm(field) {
  field = field + "";
  if (field.replace(/\s/g, "") == "") return "No column permutation was entered.\n"
  else if (!(/[0-9]+(,[0-9]+)*/.test(field)))
     return "The column permutation in invalid.\n"
  return ""
}