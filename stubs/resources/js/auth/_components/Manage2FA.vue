<template>
    <div class="manage-2fa">
        <h2 v-text="'Manage two factor authentication'" />

        <section v-if="tfaConfirmed && !showAuthenticatorSetup">
            <p class="notice">
                Two-factor authentication is already set up for your account.<br>
                <a @click="showAuthenticatorSetup = true">Click here</a> to set up a new authenticator app.
            </p>
        </section>

        <section v-if="showAuthenticatorSetup">
            <p v-if="!tfaConfirmed" class="notice">
                {{ clientName }} requires two-factor authentication to be enabled.<br>
                Please follow the steps below to set up your authenticator app.
            </p>
            <h3 v-text="'Set up an authenticator app'" />
            <ol>
                <li>
                    <p>Install an authenticator app on your phone.</p>
                    <table>
                        <tbody>
                        <tr>
                            <td>2FAS</td>
                            <td>
                                <a href="https://apps.apple.com/us/app/2fas-auth/id1217793794" target="_blank">
                                    <i class="fab fa-apple" /> iOS
                                </a>
                            </td>
                            <td>
                                <a href="https://play.google.com/store/apps/details?id=com.twofasapp" target="_blank">
                                    <i class="fab fa-android" /> Android
                                </a>
                            </td>
                        </tr>

                        <tr>
                            <td>Authy</td>
                            <td>
                                <a href="https://apps.apple.com/us/app/twilio-authy/id494168017" target="_blank">
                                    <i class="fab fa-apple" /> iOS
                                </a>
                            </td>
                            <td>
                                <a href="https://apps.apple.com/us/app/twilio-authy/id494168017" target="_blank">
                                    <i class="fab fa-android" /> Android
                                </a>
                            </td>
                        </tr>

                        <tr>
                            <td>Google Authenticator</td>
                            <td>
                                <a href="https://apps.apple.com/us/app/google-authenticator/id388497605" target="_blank">
                                    <i class="fab fa-apple" /> iOS
                                </a>
                            </td>
                            <td>
                                <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" target="_blank">
                                    <i class="fab fa-android" /> Android
                                </a>
                            </td>
                        </tr>

                        <tr>
                            <td>Microsoft Authenticator</td>
                            <td>
                                <a href="https://apps.apple.com/us/app/microsoft-authenticator/id983156458" target="_blank">
                                    <i class="fab fa-apple" /> iOS
                                </a>
                            </td>
                            <td>
                                <a href="https://play.google.com/store/apps/details?id=com.azure.authenticator" target="_blank">
                                    <i class="fab fa-android" /> Android
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </li>

                <li>
                    <p>Scan the QR code from your authenticator app below to get set up:</p>
                    <a :href="qrCodeUrl"><div v-html="qrCode" style="margin: 20px;"/></a>
                </li>

                <li v-if="!tfaConfirmed">
                    <p style="margin-bottom: 10px;">
                        Confirm access to your account by entering the 6 digit code provided by your authenticator app.
                    </p>

                    <form v-if="!showConfirmSuccessMessage">
                        <label
                            for="code"
                            style="font-weight: bold; margin-right: 0.5em;"
                            v-text="'Code'"
                        />
                        <input
                            type="text"
                            id="code"
                            autofocus
                            placeholder="123456"
                            v-model="confCode"
                        />
                        <button
                            type="submit"
                            v-text="'Confirm'"
                            style="margin-left: 1em;"
                            @click.prevent="confirm"
                        />
                    </form>

                    <p v-if="showConfirmSuccessMessage" class="notice">
                        Success! Two-factor authentication has been set up for your account.<br>
                        You may now continue to use the dashboard as usual.
                    </p>

                    <p v-if="showConfirmErrorMessage" class="notice">
                        There was a problem confirming your two-factor authentication setup.<br>
                        Please try again or contact an administrator for assistance.
                    </p>
                </li>
            </ol>
        </section>

        <section v-if="(tfaConfirmed || showConfirmSuccessMessage) && myRecoveryCodes">
            <h3 v-text="'Recovery codes'" />
            <p>Store these recovery codes in a safe place. They can be used to recover access to your account if you lose your two-factor device.</p>
            <p class="note">Note: Each code can only be used one time. Refresh or revisit this page to see your current codes.</p>
            <ul>
                <li v-for="code in myRecoveryCodes">
                    {{ code }}
                    <i
                        class="fas fa-copy"
                        @click="copyToClipboard($event, code)"
                        style="margin-left: 1em;"
                    />
                    <!-- TODO animation to confirm copy -->
                </li>
            </ul>
        </section>

<!--        TODO: (enable/disable [not for bhsla])-->
    </div>
</template>

<script>
import axios from 'axios';


export default {
    props: {
        qrCode: {
            type: String,
        },

        qrCodeUrl: {
            type: String,
        },

        tfaConfirmed: {
            type: Boolean,
        },

        recoveryCodes: {
            type: Array,
        },

        clientName: {
            type: String,
        },
    },

    data() {
        return {
            confCode: '',
            myRecoveryCodes: this.recoveryCodes,
            showAuthenticatorSetup: !this.tfaConfirmed,
            showConfirmSuccessMessage: false,
            showConfirmErrorMessage: false,
        };
    },
    methods: {
        confirm() {
            this.showConfirmErrorMessage = false;

            const vue = this;
            axios.post('/admin/user/confirmed-two-factor-authentication', {
                code: this.confCode,
            })
                .then(() => {
                    this.showConfirmSuccessMessage = true;

                    axios.get('/admin/user/two-factor-recovery-codes')
                        .then(response => {
                            console.debug(response.data);
                            vue.myRecoveryCodes = response.data;
                        })
                        .catch(() => {
                            console.debug('error');
                        })
                })
                .catch(() => {
                    vue.showConfirmErrorMessage = true;
                })
            ;
        },

        copyToClipboard($event, text) {
            console.debug($event);
            navigator.clipboard.writeText(text)
                .then(() => {
                    const el = $event.target;
                    el.classList.add('show-msg');
                    setTimeout(() => {
                        el.classList.remove('show-msg');
                    }, 1000);
                })
            ;
        },
    },
};
</script>

<style lang="scss">
.manage-2fa {
    h3 {
        font-size: 140%;
        text-decoration: underline;
    }

    .note {
        font-size: 0.8em;
        color: #666;
        font-style: italic;
    }

    ul {
        list-style-type: disc;
    }

    ul, ol {
        padding: 10px 0 0 30px;

        li {
            margin-bottom: 5px;
        }
    }

    .notice {
        padding: 20px;
        border-radius: 10px;
        display: inline-block;
        max-width: 800px;
        text-align: center;
        background: #f29320;
        color: #0c346f;
        margin: 10px 0;
    }

    form label {
        display: inline-block;
    }

    .fa-copy {
        position: relative;
    }
    .fa-copy::after {
        content: "Copied!";
        position: absolute;
        top: 0;
        right: 1em;
        transform: translateX(140%) translateY(0);
        font-size: 14px;
        color: #f29320;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease-out;
    }

    @keyframes moveRightFadeOut {
        0% {
            transform: translateX(140%) translateY(0);
            opacity: 1;
        }
        100% {
            transform: translateX(240%) translateY(0);
            opacity: 0;
        }
    }

    .fa-copy.show-msg::after {
        opacity: 1;
        animation: moveRightFadeOut 1s forwards;
    }

    @keyframes fadeToBlack {
        0% {
            color: #f29320;
        }
        100% {
            color: black;
        }
    }

    .fa-copy.show-msg {
        color: #f29320;
        animation: fadeToBlack 1s forwards;
    }

}
</style>
