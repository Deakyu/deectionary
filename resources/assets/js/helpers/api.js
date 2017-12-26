import Auth from './auth';

export function get(url) {
    return axios({
        method: 'get',
        url: url,
        headers: {
            'Authorization': `Bearer ${Auth.state.api_token}`
        }
    });
}
export function post(url, data) {
    return axios({
        method: 'post',
        url: url,
        data: data,
        headers: {
            'Authorization': `Bearer ${Auth.state.api_token}`
        }
    });
}

export function userLiked(url, data) {
    return axios({
        method: 'post',
        url: url,
        data: data,
        headers: {
            'Authorization': `Bearer ${Auth.state.api_token}`
        }
    })
}

export function userWord(url, data) {
    return axios({
        method: 'post',
        url: url,
        data: data,
        headers: {
            'Authorization': `Bearer ${Auth.state.api_token}`
        }
    })
}

export function deleteWord(url) {
    return axios({
        method: 'delete',
        url: url,
        headers: {
            'Authorization': `Bearer ${Auth.state.api_token}`
        }
    });
}

export function searchWord(param) {
    return axios({
        method: 'get',
        url: 'https://wordsapiv1.p.mashape.com/words/' + param,
        headers: {
            'X-Mashape-Key': '5nmfp8Z4wymshpdj0A6eB7rzM0bGp1AjDrAjsnBqXHzGPAmiBk',
            'Content-Type': 'application/json'
        }
    });
}