import React, { Component } from 'react';

class Game extends Component {
    render() {
        return (
            <div className="game">
              Game on {this.props.gameId}
            </div>
        );
    }
}

export default Game;
