# api

CSRF mitigation and server-side validation

Please view session.php line 99 for CSRF mitigation and you can find server-side validation throughout file.



Comment on each of the 3rd party frameworks used, why was it chosen

Bulma was choosen as it is a easy to use CSS frame work, that can easily be used with React.

React was choosen as it is a leading technology that is used in the industry, that can be scaled to suit larger projects.

Bouncer.js is used for validation and is easy to implement.



What other technologies did you investigate in order to settle on a path

Architectural frame works where React, Vue, Angular. React was selected due to reason given above.

https://reactjs.org/

CSS frame works Bulma, Bootstrap, uikit. Bulma was selected for its simplicity 

https://bulma.io/

3rd party validation frame works Boucner.js, validatejs

https://github.com/cferdinandi/bouncer

Describe why you chose this particular encryption technology

password_hash is easy to implent and very user friendly.
The result hash from password_hash() is secure because: It uses a strong hashing algorithm. It adds a random salt to prevent rainbow tables and dictionary attacks.
