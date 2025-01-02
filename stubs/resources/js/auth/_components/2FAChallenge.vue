<template>
    <div class="mfa-challenge">
        <form method="POST" action="/admin/two-factor-challenge">
<!--            @csrf-->
            <input type="hidden" name="_token" :value="csrfToken" autocomplete="off">

            <section v-show="!useRecoveryCode">
                <p class="cta">
                    Confirm access to your account by entering the 6 digit code provided by your authenticator app.
                </p>

                <div>
                    <label for="code">Code</label>
                    <input type="text" id="code" name="code" autofocus autocomplete="one-time-code" />
                    <button type="submit" v-text="'Login'" />
                </div>
            </section>

            <section v-show="useRecoveryCode">
                <p class="cta">
                    Confirm access to your account by entering one of your emergency recovery codes.
                </p>

                <div>
                    <label for="recovery_code">Recovery code</label>
                    <input type="text" id="recovery_code" name="recovery_code" autocomplete="one-time-code" />
                    <button type="submit" v-text="'Login'" />
                </div>
            </section>

        </form>

        <p class="note" v-if="!useRecoveryCode">
            Don't have access to your authenticator app? Use one of your recovery codes instead.<br>
            <button
                v-text="'Use a recovery code'"
                @click.prevent="useRecoveryCode = true"
            />
        </p>

        <p class="note" v-if="useRecoveryCode">
            Prefer to use a code from your authenticator app?<br>
            <button
                v-text="'Use a authenticator app'"
                @click.prevent="useRecoveryCode = false"
            />
        </p>


    </div>
</template>

<script>
export default {
    props: {
        csrfToken: String,
    },

    data() {
        return {
            useRecoveryCode: false,
        };
    },
};
</script>

<style lang="scss">
.mfa-challenge {
    .cta {
        font-weight: bold;
    }

    .note {
        //font-size: 90%;
        color: #0000fe;
        //padding: 10px;
        //border: 1px solid #000032;
        //border-radius: 10px;
        margin-top: 40px;
    }

    button[type=submit] {
        margin-left: 30px;
    }
}
</style>
