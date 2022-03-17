import React, { Component } from 'react';

class User extends Component {
    render() {
        return (
            <div className="login">
                <div className="token">{this.props.token}</div>
                <div className="name">{this.props.name}</div>
            </div>
        );
    }
}

export default User;
