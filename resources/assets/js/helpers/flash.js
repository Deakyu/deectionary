export default {
    state: {
        success: null,
        error: null
    },
    setSuccess(msg) {
        this.state.success = msg;
        setTimeout(() => {
            this.removeSuccess();
        }, 3000);
    },
    setError(msg) {
        this.state.error = msg;
        setTimeout(() => {
            this.removeError();
        }, 3000);
    },
    removeSuccess() {
        this.state.success = null;
    },
    removeError() {
        this.state.error = null;
    }
}