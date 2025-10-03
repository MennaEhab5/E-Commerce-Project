<div>
    <footer x-data="footerForm()" class="bg-slate-100 shadow-sm px-4 py-8 relative">
        <div class="container max-w-lg mx-auto text-center space-y-4">

            <h2 class="text-xl md:text-2xl font-semibold text-black">Contact Us</h2>
            <p class="text-slate-700">
                We'd love to hear from you! Fill out the form below and we'll get back to you soon.
            </p>

            <form @submit.prevent="submitForm" class="grid gap-3 text-left">
                @csrf
                <input type="text" name="name" placeholder="Your Name" x-model="formData.name" required
                    class="rounded-md border border-gray-300 focus:ring-1 focus:ring-gray-500 outline-none p-2 w-full transition" />
                <input type="email" name="email" placeholder="Your Email" x-model="formData.email" required
                    class="rounded-md border border-gray-300 focus:ring-1 focus:ring-gray-500 outline-none p-2 w-full transition" />
                <textarea name="message" placeholder="Your Message" rows="4" x-model="formData.message" required
                    class="rounded-md border border-gray-300 focus:ring-1 focus:ring-gray-500 outline-none p-2 w-full transition"></textarea>

                <button type="submit" :disabled="loading"
                    class="bg-green-500 hover:bg-green-600 text-white py-2 rounded font-semibold transition"
                    x-text="loading ? 'Sending...' : 'Send Message'"></button>
            </form>

        </div>

        <!-- Toast Notification -->
        <div x-show="toast.show" x-transition
             x-text="toast.message"
             :class="toast.type === 'success' ? 'bg-green-500' : 'bg-red-500'"
             class="fixed bottom-6 right-6 text-white px-4 py-2 rounded shadow-lg">
        </div>
    </footer>

    <!-- Alpine + Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <script>
        function footerForm() {
            // هنا حطينا الرابط بالطريقة الصحيحة
            const contactRoute = "{{ route('contact.send') }}";

            return {
                formData: { name: '', email: '', message: '' },
                loading: false,
                toast: { show: false, message: '', type: 'success' },

                showToast(message, type = 'success') {
                    this.toast.message = message;
                    this.toast.type = type;
                    this.toast.show = true;
                    setTimeout(() => { this.toast.show = false }, 3000);
                },

                submitForm() {
                    this.loading = true;

                    axios.post(contactRoute, this.formData, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
                        }
                    })
                    .then(res => {
                        this.showToast(res.data.message, 'success');
                        this.formData = { name: '', email: '', message: '' };
                    })
                    .catch(err => {
                        if(err.response && err.response.data && err.response.data.errors){
                            let messages = Object.values(err.response.data.errors)
                                                 .flat()
                                                 .join(" | ");
                            this.showToast(messages, 'error');
                        } else {
                            this.showToast('Something went wrong', 'error');
                        }
                    })
                    .finally(() => { this.loading = false });
                }
            }
        }
    </script>
</div>
