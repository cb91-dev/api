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

### `Other Dependencies` and location to find them in TeamWork

Open console in root of project and run the following:

### `BULMA`

v0.9.3
yarn add bulma

     Bulma has been used throughout this project as it is a CSS framework

### `Full Calendar`

v5.10
yarn add --save @fullcalendar/react @fullcalendar/daygrid @fullcalendar/interaction

    Full Calendar is only visible to those user assigned the additional role of manager. Full Calendar can be found in the admin link from menu, this plugin was used to give mananger a clear view of rostered on staff for each day.

### `React-Datetime`

v3.1.1
yarn add react-datetime

    This plugin is used when creating a shift and selecting the correct time for that shift to occur, this gives the admin a easy to use format that full calendar will take.

### `Moment`

v2.29.1
yarn add --save moment react-moment
yarn add --save moment-timezone

    This plugin works in with the react-datetime and is a dependency to use date-time.

It should be noted that if any CSS changes are needed a SASS complier will be needed in choosen code editor. The next items mentioned are extensions in VS Code.

### `Live Server`

v5.6.1

### `Live Sass Compiler`

v3.0.0
