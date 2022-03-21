import React, { Component } from 'react';

class GameListItem extends Component {
    // constructor(props) {
    //     super(props);
    // }
    //
    // componentDidMount() {
    // }

    handleClick(e) {
        e.preventDefault();
        console.log('Selected item: ' + e.target.id);
        e.target.className = e.target.className + " selected";
    }

    render() {
        return (
            <div id={this.props.id} className={`gameListItem ${this.props.status}`} onClick={this.handleClick} >
                <div className="id">{this.props.id}</div>
                <div className="status">{this.props.status}</div>
                <div className="timeLeft">{this.props.timeLeft}</div>
            </div>
        )
    }
}

export default GameListItem;
