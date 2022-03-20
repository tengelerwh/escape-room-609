import React, { Component, createRef } from 'react';
import Client from './Client';
import eventDispatcher from './Event/EventDispatcher';
import User from "./User";

class Login extends Component {
    constructor(props) {
        super(props);
        console.log('Login: constructor');
        this.state = {
            token: null,
            loggedIn: false,
            name: '',
            error: ''
        }
        this.usernameElement = createRef();
        this.passwordElement = createRef();

        this.handleSubmit = this.handleSubmit.bind(this);
    }

    componentDidMount() {
        eventDispatcher.on("login.error", (error) => {
            console.log('Login: message login.error');
            this.setState({token: null, loggedIn: false, name: '', error: error})
        });
        eventDispatcher.on("login.success", (data) => {
            console.log('Login: message login.success' + JSON.stringify(data));
            let newState = {
                token: data.token,
                loggedIn: data.loggedIn,
                name: data.name,
                error: ''
            };
            this.setState(newState);
        });
    }

    componentWillUnmount() {
        eventDispatcher.remove('login.success');
        eventDispatcher.remove('login.error');
    }

    handleSubmit(e) {
        e.preventDefault();
        let data = {
            '_username': this.usernameElement.current.value.toString(),
            '_password': this.passwordElement.current.value.toString()
        };

        Client.sendRequest(data, 'auth/login', 'login');
    }

    render() {
        if (true === this.state.loggedIn) {
            return (
                <User name={this.state.name} token={this.state.token}/>
            );
        }
        return (
            <div>
                <div className="error">{this.state.error}</div>
                <div className="loginForm">
                    <input ref={this.usernameElement} name="username" placeholder="your.email@your.domain.nl"/>
                    <input ref={this.passwordElement} type="password" name="password" />
                    <button onClick={this.handleSubmit}>Login</button>
                </div>
            </div>
        );
    }
}

export default Login;
