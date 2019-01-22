export function getSessionData() {
    let sessionData = localStorage.getItem('session');

    if (!sessionData) {
        $.get('/user/session/info', function (data) {
            localStorage.setItem('session', JSON.stringify(data));
        });

        sessionData = localStorage.getItem('session');
    }

    return JSON.parse(sessionData);
}

export function updateSessionTtl(sessionData, currentTimestamp) {
    if (sessionData.created + sessionData.lifetime - currentTimestamp <= 300) {
        $.get('/user/session/update-ttl', function (data) {
            localStorage.setItem('session', JSON.stringify(data));
        })
    }
}

export function init() {
    let sessionData = this.getSessionData();
    let currentTimestamp = + new Date();
    this.updateSessionTtl(sessionData, currentTimestamp);
}
