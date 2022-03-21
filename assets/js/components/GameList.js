import React, { Component } from 'react';
import eventDispatcher from './Event/EventDispatcher';
import Client from './Client';
import GameListItem from './GameListItem';

class GameList extends Component {
    constructor(props) {
        super(props);

        this.state = {
            list: []
        };
    }
    componentDidMount() {
        eventDispatcher.on("game.list.error", (error) => {
            console.log('GameList: message game.list.error');
            this.setState({token: null, loggedIn: false, name: '', error: error})
        });
        eventDispatcher.on("game.list.success", (data) => {
            console.log('GameList: message game.list.success' + JSON.stringify(data));
            this.setState({list: data.games});
        });

        Client.get('game/list', 'game.list', this.props.accessToken);
    }

    componentWillUnmount() {
        eventDispatcher.remove('game.list.success');
        eventDispatcher.remove('game.list.error');
    }

    render() {
        return (
            <>
                <ul className="gameList">
                    {this.state.list.map(item => (<GameListItem key={item.uuid} id={item.uuid} status={item.status} timeLeft={item.timeLeft} />))}
                </ul>
            </>
        )
    }
}

export default GameList;
