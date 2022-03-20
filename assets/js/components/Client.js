import React from 'react';
import eventDispatcher from './Event/EventDispatcher';

const Client = {
    sendRequest(requestData, path, messageType) {
        const basePath = 'http://localhost:9081/';
        const requestOptions = {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
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
