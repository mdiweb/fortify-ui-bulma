<template>
    <div class="update-password">
        <h2 v-text="'Update password'" />

        <form>
            <label-input
                label="Current password"
                type="password"
                required
                v-model="currentPassword"
                :validator="$v.currentPassword"
                error-message="Current password is required."
            />

            <label-input
                label="New password"
                type="password"
                required
                v-model="newPassword"
                :validator="$v.newPassword"
                error-message="New password is required."
            />

            <label-input
                label="Confirm new password"
                type="password"
                required
                v-model="newPassword2"
                :validator="$v.newPassword2"
                error-message="New passwords must match."
            />

            <label-input label=" ">
                <template v-slot:default>
                    <button
                        v-text="'Update password'"
                        @click.prevent="doSubmit"
                        :disabled="$v.$invalid"
                    />
                </template>
            </label-input>
        </form>


        <label-input label=" " v-if="state !== 'pending'">
            <template v-slot:default>
                <div class="success" v-if="state === 'success'">
                    <p v-text="'Password changed successfully.'" class="result" />
                </div>

                <div class="errors" v-if="state === 'error'">
                    <p v-text="'Password change failed.'" class="result" />
                    <ul v-if="errors.length">
                        <li v-for="error in errors" v-text="error" />
                    </ul>
                </div>
            </template>
        </label-input>

<!--        {{ state }}-->
<!--        <pre>{{ errors }}</pre>-->

<!--        <pre>{{ $v }}</pre>-->
    </div>
</template>

<script>
import LabelInput from '../../_components/form/LabelInput.vue';

import axios from 'axios';
import { validationMixin } from 'vuelidate';
import { required, sameAs } from 'vuelidate/lib/validators';
// import {actionButton} from '../../dashboard/_mixins/actionButton';

export default {
    components: {
        LabelInput,
    },

    mixins: [
        validationMixin,
    ],

    props: {
        csrfToken: String,
    },

    data() {
        return {
            currentPassword: '',
            newPassword: '',
            newPassword2: '',

            state: 'pending',
            errors: [],
        };
    },

    validations: {
        currentPassword: { required },
        newPassword: { required },
        newPassword2: { sameAs: sameAs('newPassword') },
    },

    watch: {
        '$v.$anyDirty': {
            handler(cur) {
                if (cur) {
                    this.state = 'pending';
                }
            },
            // deep: true,
        },
    },

    methods: {
        doSubmit() {
            this.$v.$touch();
            console.log('doSubmit', this.currentPassword, this.newPassword, this.newPassword2);
            if (this.$v.$invalid) {
                return;
            }
            axios.put('/admin/user/password', {
                _token: this.csrfToken,
                current_password: this.currentPassword,
                password: this.newPassword,
                password_confirmation: this.newPassword2,
            })
                .then(response => {
                    console.debug('response', response);
                    this.currentPassword = '';
                    this.newPassword = '';
                    this.newPassword2 = '';
                    this.state = 'success';
                    this.$nextTick(() => {
                        this.$v.$reset();
                    });
                })
                .catch(error => {
                    console.error('error', error.response.data);

                    this.state = 'error';
                    this.errors.splice(0, this.errors.length, ...(Object.values(error.response.data.errors)).flat());
                })
            ;
        },
    }
};
</script>

<style lang="scss">
.update-password {
    .field-label label {
        width: 13em;
    }

    button[disabled] {
        opacity: 0.7;
        color: #555555;
    }

    .success, .errors {
        margin-top: 40px;

        .result {
            font-weight: bold;
        }
    }

    ul {
        list-style: disc;
        padding: 10px 20px;
    }
}
</style>
