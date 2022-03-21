import React from 'react';
import eventDispatcher from './Event/EventDispatcher';

const Client = {
    post(requestData, path, messageType, accessToken) {
        const basePath = 'http://localhost:9081/';
        let headers = { 'Content-Type': 'application/json' };
        if (null !== accessToken) {
            headers['X-ACCESS-TOKEN'] = accessToken;
        }
        const requestOptions = {
            method: 'POST',
            headers: headers,
            body: JSON.stringify(requestData)
        };
        console.log('sending request to ' + basePath + path);
        fetch(basePath + path, requestOptions)
            .then(res => {
                if (false === res.ok) {
                    throw new Error(res.status + ':' + res.statusMessage);
                }
                return res.json();
            })
            .then(responseData => this.onSuccess(responseData, messageType))
            .catch(err => this.onError(err, messageType));
    },

    get(path, messageType, accessToken) {
        const basePath = 'http://localhost:9081/';
        let headers = { 'Content-Type': 'application/json' };
        if (null !== accessToken) {
            headers['X-ACCESS-TOKEN'] = accessToken;
        }
        const requestOptions = {
            method: 'GET',
            headers: headers
        };
        console.log('sending request to ' + basePath + path);
        fetch(basePath + path, requestOptions)
            .then(res => {
                if (false === res.ok) {
                    throw new Error(res.status + ':' + res.statusMessage);
                }
                return res.json();
            })
            .then(responseData => this.onSuccess(responseData, messageType))
            .catch(err => this.onError(err, messageType));
    },

    onSuccess(data, messageType) {
        const type = messageType + '.success';
        console.log("response received ");
        eventDispatcher.dispatch(type, data);
    },

    onError(error, messageType) {
        const type = messageType + '.error';
        console.log(error);
        eventDispatcher.dispatch(type, error);
    }
}

export default Client;
