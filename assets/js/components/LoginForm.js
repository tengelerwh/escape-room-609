import React, { Component, createRef } from 'react';

class LoginForm extends Component {
    constructor(props) {
        super(props);
        this.usernameElement = createRef();
        this.passwordElement = createRef();

        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleSubmit(e) {
        e.preventDefault();
        let form = e.currentTarget;
        let body = {
            '_username': this.usernameElement.current.value.toString(),
            '_password': this.passwordElement.current.value.toString()
        };
        console.log('submitting' + JSON.stringify(body) + ' to ' + this.props.submitPath);

        const requestOptions = {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(body)
        };
        fetch(this.props.submitPath, requestOptions)
            .then(response => response.json())
            .then(data => this.props.callback(data));
    }

    render() {
        return (
            <div className="loginForm">
                <input ref={this.usernameElement} name="username" placeholder="your.email@your.domain.nl"/>
                <input ref={this.passwordElement} type="password" name="password" />
                <button onClick={this.handleSubmit}>Login</button>
            </div>
        );
    }
}

export default LoginForm;
