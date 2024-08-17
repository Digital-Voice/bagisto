<v-modal-phone-number ref="phoneNumberModal"></v-modal-phone-number>

@pushOnce('scripts')
<script type="text/x-template" id="v-modal-phone-number-template">
    <div>
            <transition
                tag="div"
                name="modal-overlay"
                enter-class="duration-300 ease-out"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-class="duration-200 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    class="fixed inset-0 z-20 transition-opacity bg-gray-500 bg-opacity-50"
                    v-show="isOpen"
                ></div>
            </transition>

            <transition
                tag="div"
                name="modal-content"
                enter-class="duration-300 ease-out"
                enter-from-class="translate-y-4 opacity-0 md:translate-y-0 md:scale-95"
                enter-to-class="translate-y-0 opacity-100 md:scale-100"
                leave-class="duration-200 ease-in"
                leave-from-class="translate-y-0 opacity-100 md:scale-100"
                leave-to-class="translate-y-4 opacity-0 md:translate-y-0 md:scale-95"
            >
                <div
                    class="fixed inset-0 z-20 overflow-y-auto transition transform" v-show="isOpen"
                >
                    <div class="flex items-end justify-center min-h-full p-4 text-center sm:items-center sm:p-0">
                        <div class="absolute left-1/2 top-1/2 z-[999] w-full max-w-[475px] -translate-x-1/2 -translate-y-1/2 overflow-hidden rounded-xl bg-white p-5 max-md:w-[90%] max-sm:p-4">
                            <div class="flex gap-2.5">
                                <div>
                                    <span class="flex rounded-full border border-gray-300 p-2.5">
                                        <i class="text-3xl icon-error max-sm:text-xl"></i>
                                    </span>
                                </div>

                                <div>
                                    <div class="flex items-center justify-between gap-5 text-xl max-sm:text-lg">
                                        Enter your phone number
                                    </div>

                                    <div class="pb-5 pt-1.5 text-left text-sm text-gray-500">
                                        <x-shop::form.control-group.control
                                            type="text"
                                            class="px-6 py-4 max-md:py-3 max-sm:py-2"
                                            name="phone"
                                            v-model="phone"
                                            rules="required"
                                            :value="old('phone')"
                                            :label="trans('shop::app.checkout.onepage.address.telephone')"
                                            placeholder="01783110247"
                                            :aria-label="trans('shop::app.checkout.onepage.address.telephone')"
                                            aria-required="true"
                                        />
                                    </div>

                                    <div class="flex justify-end gap-2.5">
                                        <button
                                            type="button"
                                            class="secondary-button max-md:py-3 max-sm:px-6 max-sm:py-2.5"
                                            @click="cancel"
                                        >
                                            Cancel
                                        </button>

                                        <button
                                            type="button"
                                            class="primary-button max-md:py-3 max-sm:px-6 max-sm:py-2.5"
                                            @click="action"
                                        >
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </script>

<script type="module">
    app.component('v-modal-phone-number', {
        template: '#v-modal-phone-number-template',

        data() {
            return {
                isOpen: false,

                actionCallback: null,

                phone: localStorage.getItem('customer_phone') || '',
            };
        },

        created() {
            this.registerGlobalEvents();
        },

        methods: {
            open({
                action = () => {},
            }) {
                this.isOpen = true;

                document.body.style.overflow = 'hidden';

                this.actionCallback = action;
            },

            cancel() {
                this.isOpen = false;

                document.body.style.overflow = 'auto';
            },

            action() {
                if (!this.phone) {
                    this.$emitter.emit('add-flash', { type: 'error', message: "@lang('Phone number is required')" });

                    return;
                }

                if (!/^(?:01\d{9})$/.test(this.phone)) {
                    this.$emitter.emit('add-flash', { type: 'error', message: "@lang('Invalid phone number')" });

                    return;
                }

                localStorage.setItem('customer_phone', this.phone);

                this.isOpen = false;

                document.body.style.overflow = 'auto';

                this.actionCallback();
            },

            registerGlobalEvents() {
                this.$emitter.on('open-phone-number-modal', this.open);
            },
        }
    });
</script>
@endPushOnce