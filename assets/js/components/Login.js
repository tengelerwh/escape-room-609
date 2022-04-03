import React, { Component, createRef } from 'react';
import Client from './Client';
import eventDispatcher from './Event/EventDispatcher';
import User from "./User";

class Login extends Component {
    constructor(props) {
        super(props);
        console.log('Login: constructor');
        this.state = {
            refresh: null,
            token: null,
            loggedIn: false,
            name: '',
            error: ''
        }
        this.usernameElement = createRef();
        this.passwordElement = createRef();
        this.refreshCount = 0;

        this.state.refresh = localStorage.getItem('refresh');
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    componentDidMount() {
        eventDispatcher.on("login.error", (error) => {
            console.log('Login: message login.error');
            localStorage.removeItem('refresh');

            this.setState({token: null, loggedIn: false, name: '', error: error})
        });
        eventDispatcher.on("login.success", (data) => {
            console.log('Login: message login.success' + JSON.stringify(data));
            let newState = {
                refresh: data.refresh,
                token: data.token,
                loggedIn: data.loggedIn,
                name: data.name,
                error: ''
            };
            // persist refresh token only
            localStorage.setItem('refresh', data.refresh);
            this.setState(newState);
        });
        eventDispatcher.on("login.refresh.success", (data) => {
            console.log('Login: message login.refresh.success' + JSON.stringify(data));
            let newState = {
                refresh: data.refresh,
                token: data.token,
                loggedIn: data.loggedIn,
                name: data.name,
                error: ''
            };
            // persist refresh token only
            localStorage.setItem('refresh', data.refresh);
            this.setState(newState);
        });
        eventDispatcher.on("login.refresh.error", (error) => {
            console.log('Login Refresh : message login.refresh.error');
            localStorage.removeItem('refresh');

            this.setState({refresh: null, token: null, loggedIn: false, name: '', error: error})
        });
    }

    componentWillUnmount() {
        eventDispatcher.remove('login.success');
        eventDispatcher.remove('login.error');
        eventDispatcher.remove('login.refresh.success');
        eventDispatcher.remove('login.refresh.error');
    }

    handleSubmit(e) {
        e.preventDefault();
        let data = {
            '_username': this.usernameElement.current.value.toString(),
            '_password': this.passwordElement.current.value.toString()
        };

        Client.post(data, 'auth/login', 'login');
    }

    refreshToken() {
        this.refreshCount++;
        let data = {
            'refresh': this.state.refresh
        }
        if (this.refreshCount < 2) {
            Client.post(data, 'auth/refresh', 'login.refresh');
        }
    }

    render() {
        console.log('loggedIn: '+ this.state.loggedIn);
        if (true === this.state.loggedIn) {
            return (
                <User name={this.state.name} token={this.state.token}/>
            );
        } else {
            // do we have a refresh token
            console.log('refresh: ' + this.state.refresh);
            if ('string' === typeof this.state.refresh) {
                this.refreshToken();
                return (
                    <div>Refreshing...</div>
                );
            } else {
                console.log('refresh === null');
            }
        }
        return (
            <div>
                <div className="error">{this.state.error}</div>
                <div className="loginForm">
                    <input ref={this.usernameElement} name="username" placeholder="your.email@your.domain.nl" value="wouter@test.nl" />
                    <input ref={this.passwordElement} type="password" name="password" />
                    <button onClick={this.handleSubmit}>Login</button>
                </div>
            </div>
        );
    }
}

export default Login;
