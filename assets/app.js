/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import 'bootstrap/dist/css/bootstrap.css';
import './styles/app.css';

import React from 'react';
import ReactDOM from 'react-dom';
import Login from './js/components/Login';
import eventDispatcher from './js/components/Event/EventDispatcher';
import Game from './js/components/Game';
import GameList from './js/components/GameList';
import User from './js/components/User';

class App extends React.Component {
    constructor() {
        super();

        this.state = {
            auth: {
                token: null,
                loggedIn: false
            },
            game: {
                started: false,
                id: null
            }
        };
    }

    componentDidMount() {
        eventDispatcher.on("login.success", (data) => {
            console.log('App: message login.success');
            let newState = {
                token: data.token,
                loggedIn: data.loggedIn,
            };
            this.setState({auth: newState});
        });
        eventDispatcher.on("login.refresh.success", (data) => {
            console.log('App: message login.refresh.success');
            let newState = {
                token: data.token,
                loggedIn: data.loggedIn,
            };
            // @todo if a game is active for this client, load game data
            this.setState({auth: newState});
        });
        eventDispatcher.on("game.started", (data) => {
            console.log('App: message game.started: ' + data.id);
            let newState = {
                started: true,
                id: data.id,
            };
            this.setState({game: newState});
        });
    }

    componentWillUnmount() {
        eventDispatcher.remove('login.success');
        eventDispatcher.remove('login.error');
        eventDispatcher.remove('login.refresh.success');
        eventDispatcher.remove('login.refresh.error');
    }

    render() {
        let page;
        if (true === this.state.auth.loggedIn) {
            if (true === this.state.game.started) {
                page = <Game accessToken={this.state.auth.token} gameId={this.state.game.id}/>
            } else {
                page = <GameList accessToken={this.state.auth.token} />
            }
        }

        return (
            <div>
                <Login  />
                {page}
            </div>
        );
    }
}

ReactDOM.render(<App />, document.getElementById('root'));
