/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
// import './bootstrap';

import React from 'react';
import ReactDOM from 'react-dom';


class App extends React.Component {
    constructor() {
        super();

        this.state = {
            path: 'http://localhost:8081/api/v1/',
            loggedIn: false
        };
    }

    // componentDidMount() {
    //     fetch(this.state.path + 'auth/login')
    //         .then(response => response.json())
    //         .then(loggedIn => {
    //             this.setState({
    //                 loggedIn
    //             });
    //         });
    // }

    render() {
        return (
            <div className="login">Login form</div>
        );
    }
}

ReactDOM.render(<App />, document.getElementById('root'));
