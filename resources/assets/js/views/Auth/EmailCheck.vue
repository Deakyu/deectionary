<template>
    <div>
        <img src="/images/Double_Ring.gif" class="loader" v-show="isProcessing">
        <div class="email-check" v-show="!isProcessing">
            <h1 class="email-check__title">We sent you a verification email, please check!</h1>
            <span class="email-check__icon">
                <i class="fa fa-envelope-open-o" 
                :class="{flyin: mounted}" aria-hidden="true"></i>
            </span>
        </div>
    </div>
    
</template>

<script>
    export default {
        data() {
            return {
                mounted: false,
                isProcessing: false,
            };
        },
        mounted() {
            this.isProcessing = true;
            setTimeout(() => {
                this.mounted = true;
                this.isProcessing = false;
            }, 100);
        }
    }
</script>