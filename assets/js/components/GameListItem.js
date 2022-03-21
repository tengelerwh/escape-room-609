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
