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
import LoginForm from './js/components/LoginForm';
import User from './js/components/User';

class App extends React.Component {
    constructor() {
        super();

        this.state = {
            path: 'http://localhost:9081/',
            auth: {
                'token': null,
                'loggedIn': false,
                'name': ''
            }
        };
        this.login = this.login.bind(this);
    }

    componentDidMount() {
        // fetch(this.state.path + 'auth/login')
        //     .then(response => response.json())
        //     .then(authData => {
        //         this.setState({auth: authData});
        //     });
    }

    login(data) {
        console.log('App login data returned' + data.name);
        this.setState({auth: data});
    }

    render() {
        if (true === this.state.auth.loggedIn) {
            return (
                <User name={this.state.auth.name} token={this.state.auth.token}/>
            );
        }
        return (
            <LoginForm submitPath={this.state.path + 'auth/login'} callback={this.login} />
        );
    }
}

ReactDOM.render(<App />, document.getElementById('root'));
