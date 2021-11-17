## This is the frontend of TeamWork application, seek api files in repo to view.

## TEAMWORK

v1.0.1

# Getting Started with Create React App

This project was bootstrapped with [Create React App](https://github.com/facebook/create-react-app).

## Available Scripts

In the project directory, you can run:

### `yarn start`

Runs the app in the development mode.\
Open [http://localhost:3000](http://localhost:3000) to view it in the browser.

The page will reload if you make edits.\
You will also see any lint errors in the console.

### `yarn test`

Launches the test runner in the interactive watch mode.\
See the section about [running tests](https://facebook.github.io/create-react-app/docs/running-tests) for more information.

### `yarn build`

Builds the app for production to the `build` folder.\
It correctly bundles React in production mode and optimizes the build for the best performance.

The build is minified and the filenames include the hashes.\
Your app is ready to be deployed!

See the section about [deployment](https://facebook.github.io/create-react-app/docs/deployment) for more information.

### `yarn eject`

**Note: this is a one-way operation. Once you `eject`, you can’t go back!**

If you aren’t satisfied with the build tool and configuration choices, you can `eject` at any time. This command will remove the single build dependency from your project.

Instead, it will copy all the configuration files and the transitive dependencies (webpack, Babel, ESLint, etc) right into your project so you have full control over them. All of the commands except `eject` will still work, but they will point to the copied scripts so you can tweak them. At this point you’re on your own.

You don’t have to ever use `eject`. The curated feature set is suitable for small and middle deployments, and you shouldn’t feel obligated to use this feature. However we understand that this tool wouldn’t be useful if you couldn’t customize it when you are ready for it.

## Learn More

You can learn more in the [Create React App documentation](https://facebook.github.io/create-react-app/docs/getting-started).

To learn React, check out the [React documentation](https://reactjs.org/).

### Code Splitting

This section has moved here: [https://facebook.github.io/create-react-app/docs/code-splitting](https://facebook.github.io/create-react-app/docs/code-splitting)

### Analyzing the Bundle Size

This section has moved here: [https://facebook.github.io/create-react-app/docs/analyzing-the-bundle-size](https://facebook.github.io/create-react-app/docs/analyzing-the-bundle-size)

### Making a Progressive Web App

This section has moved here: [https://facebook.github.io/create-react-app/docs/making-a-progressive-web-app](https://facebook.github.io/create-react-app/docs/making-a-progressive-web-app)

### Advanced Configuration

This section has moved here: [https://facebook.github.io/create-react-app/docs/advanced-configuration](https://facebook.github.io/create-react-app/docs/advanced-configuration)

### Deployment

This section has moved here: [https://facebook.github.io/create-react-app/docs/deployment](https://facebook.github.io/create-react-app/docs/deployment)

### `yarn build` fails to minify

This section has moved here: [https://facebook.github.io/create-react-app/docs/troubleshooting#npm-run-build-fails-to-minify](https://facebook.github.io/create-react-app/docs/troubleshooting#npm-run-build-fails-to-minify)

## `Front End`

HTML
HTML5
The HyperText Markup Language, or HTML is the standard markup language for documents designed to be displayed in a web browser. This technology was used throughout application to ensure it could be render by browsers.

CSS
v2.1
Cascading Style Sheets is a style sheet language used for describing the presentation of a document written in a markup language such as HTML. This was used throughout project to styling it.

SCSS
v3.5.6
Sassy Cascading Style Sheets is a special type of file for SASS, a program written in Ruby that assembles CSS style sheets for a browser, and for information, SASS adds lots of additional functionality to CSS like variables, nesting and more which can make writing CSS easier and faster.

JAVASCRIPT
ES2015
JavaScript is a text-based programming language used both on the client-side and server-side that allows you to make web pages interactive and much more. A list below highlights some of the key parts or features of javascript that where used for in this project.

        1.Create interaction for user, example is hiding or showing different part of application depending on login status.

        2.The Fetch API provides an interface for fetching resources,including across the network and was used to interact with database.

        3.JSX is a syntax extension to JavaScript and was used along with react to render elements to page.

### `Other Dependencies` and location to find them in TeamWork

Open console in root of project and run the following:

## `REACT`

https://reactjs.org/
v17.0.2
yarn create react-app my-app
React was used to implemented admin panel for this project

### `BULMA`

https://bulma.io/
v0.9.3
yarn add bulma

     Bulma has been used throughout this project as it is a CSS framework

### `Full Calendar`

https://fullcalendar.io/
v5.10.0
yarn add --save @fullcalendar/react @fullcalendar/daygrid @fullcalendar/interaction

    Full Calendar is only visible to those user assigned the additional role of manager. Full Calendar can be found in the admin link from menu, this plugin was used to give mananger a clear view of rostered on staff for each day.

### `React-Datetime`

https://www.npmjs.com/package/react-datetime
v3.1.1
yarn add react-datetime

    This plugin is used when creating a shift and selecting the correct time for that shift to occur, this gives the admin a easy to use format that full calendar will take.

### `Moment`

https://www.npmjs.com/package/moment
v2.29.1
yarn add --save moment react-moment
yarn add --save moment-timezone

    This plugin works in with the react-datetime and is a dependency to use date-time.

It should be noted that if any CSS changes are needed a SASS complier will be needed in choosen code editor. The next items mentioned are extensions in VS Code.

### `Live Server`

        Live Server was used with VScode as it a dependency for live sass compiler

v5.6.1

### `Live Sass Compiler`

        Live Sass Compiler was used was VScode to enable the use of Scss in development.

v3.0.0

## `Webserver`

### `Api`

The api built for the application is using PHP,PDO,SQL.

PHP
https://www.php.net/
v7.4.12

    The features of this technology used in development of project consist classes and objects , these where used to create a connection class and a session class to be called upon throughout the web service.

PDO
https://www.php.net/
v7.4.12

    PDO refers to PHP data object which is a PHP extension that defines a lightweight and consistent interface for accessing a database in PHP. This feature of PHP was used in connection file while interacting with DB.

SQL
v5.7.32
SQL is a domain-specific language used in programming and designed for managing data held in a relational database management system, or for stream processing in a relational data stream management system. This technology was used to create the database for this project, along with using SQL statements in PHP.

## `Development Tools`

## `Thunder Client`

v1.9.1
Thunder Client is a lightweight Rest API Client Extension for Visual Studio Code,this was used to test rest API.

## `Chrome Dev Tools`

https://developer.chrome.com/docs/devtools/
Chrome dev tools was used throughout project to aid in troubleshooting and problem solving.
