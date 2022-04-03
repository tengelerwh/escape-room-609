import React, { Component } from 'react';
import eventDispatcher from './Event/EventDispatcher';

class GameListItem extends Component {
    constructor(props) {
        super(props);
        this.handleClick = this.handleClick.bind(this);
    }
    //
    // componentDidMount() {
    // }

    handleClick(e) {
        e.preventDefault();
        console.log('Selected item: ' + this.props.id);
        e.target.className = e.target.className + " selected";
        eventDispatcher.dispatch('game.started', {id: this.props.id});
    }

    render() {
        return (
            <tr id={this.props.id} className={`gameListItem ${this.props.status}`} onClick={this.handleClick} >
                <td className="num">{this.props.count}</td>
                <td className="id">{this.props.id}</td>
                <td className="status">{this.props.status}</td>
                <td className="timeLeft">{this.props.timeLeft}</td>
            </tr>
        )
    }
}

export default GameListItem;
