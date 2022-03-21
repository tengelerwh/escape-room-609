import React, { Component } from 'react';
import eventDispatcher from './Event/EventDispatcher';
import Client from './Client';
import GameListItem from './GameListItem';
import Table from 'react-bootstrap/Table';

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
        let row = 1;
        return (
            <>
                <Table responsive="sm" className="gameList">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Uuid</th>
                        <th>Status</th>
                        <th>Time left</th>
                    </tr>
                    </thead>
                    <tbody>
                    {this.state.list.map(item => (<GameListItem key={item.uuid} count={row++} id={item.uuid} status={item.status} timeLeft={item.timeLeft} />))}
                    </tbody>
                </Table>
            </>
        )
    }
}

export default GameList;
