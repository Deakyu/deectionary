export default {
    state: {
        api_token: null,
        user_id: null,
        email: null,
    },
    initialize() {
        this.state.api_token = localStorage.getItem('api_token');
        this.state.user_id = localStorage.getItem('user_id');
        this.state.email = localStorage.getItem('email');
    },
    set(api_token, user_id, email) {
        localStorage.setItem('api_token', api_token);
        localStorage.setItem('user_id', user_id);
        localStorage.setItem('email', email);
        this.initialize();
    },
    remove() {
        localStorage.removeItem('api_token');
        localStorage.removeItem('user_id');
        localStorage.removeItem('email');
        this.initialize();
    }
}