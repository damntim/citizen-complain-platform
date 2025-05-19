



<div id="ticket-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
            
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-4 py-3 sm:px-6 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-white">
                    <span data-translate="submit_ticket_title">Gutanga Ikibazo</span>
                </h3>
                <button type="button" class="close-modal text-white hover:text-gray-200">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            
            <div class="px-4 pt-5 sm:px-6">
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-rwandan-green h-2.5 rounded-full progress-bar" style="width: 20%"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-500 mt-1">
                    <span data-translate="step_personal">Amakuru Yawe</span>
                    <span data-translate="step_institution">Urwego</span>
                    <span data-translate="step_problem">Ikibazo</span>
                    <span data-translate="step_notification">Imenyesha</span>
                    <span data-translate="step_review">Isubiramo</span>
                </div>
            </div>

            
            <form id="ticket-form">
                
                <div class="step-content" id="step-1">
                    <div class="px-4 py-5 sm:p-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">
                            <span data-translate="personal_info_title">Amakuru Yawe</span>
                        </h4>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="full_name" class="block text-sm font-medium text-gray-700">
                                    <span data-translate="full_name">Amazina Yombi</span> *
                                </label>
                                <input type="text" name="full_name" id="full_name" required class="mt-1 focus:ring-rwandan-green focus:border-rwandan-green block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">
                                    <span data-translate="phone_number">Telefoni</span> *
                                </label>
                                <input type="tel" name="phone" id="phone" required pattern="[0-9]{10}" class="mt-1 focus:ring-rwandan-green focus:border-rwandan-green block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <p class="text-xs text-gray-500 mt-1">
                                    <span data-translate="phone_format">Urugero: 07XXXXXXXX</span>
                                </p>
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">
                                    <span data-translate="email">Imeri</span> <span class="text-xs text-gray-500">(<span data-translate="optional">Si ngombwa</span>)</span>
                                </label>
                                <input type="email" name="email" id="email" class="mt-1 focus:ring-rwandan-green focus:border-rwandan-green block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                        </div>

                        <h5 class="text-md font-medium text-gray-900 mt-6 mb-3">
                            <span data-translate="location">Aho Utuye</span> *
                        </h5>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="province" class="block text-sm font-medium text-gray-700">
                                    <span data-translate="province">Intara</span>
                                </label>
                                <select id="province" name="province" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-rwandan-green focus:border-rwandan-green sm:text-sm">
                                    <option value="" data-translate="select_province">Hitamo Intara</option>
                                </select>
                            </div>
                            <div>
                                <label for="district" class="block text-sm font-medium text-gray-700">
                                    <span data-translate="district">Akarere</span>
                                </label>
                                <select id="district" name="district" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-rwandan-green focus:border-rwandan-green sm:text-sm">
                                    <option value="" data-translate="select_district">Hitamo Akarere</option>
                                </select>
                            </div>
                            <div>
                                <label for="sector" class="block text-sm font-medium text-gray-700">
                                    <span data-translate="sector">Umurenge</span>
                                </label>
                                <select id="sector" name="sector" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-rwandan-green focus:border-rwandan-green sm:text-sm">
                                    <option value="" data-translate="select_sector">Hitamo Umurenge</option>
                                </select>
                            </div>
                            <div>
                                <label for="cell" class="block text-sm font-medium text-gray-700">
                                    <span data-translate="cell">Akagari</span>
                                </label>
                                <select id="cell" name="cell" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-rwandan-green focus:border-rwandan-green sm:text-sm">
                                    <option value="" data-translate="select_cell">Hitamo Akagari</option>
                                </select>
                            </div>
                            <div>
                                <label for="village" class="block text-sm font-medium text-gray-700">
                                    <span data-translate="village">Umudugudu</span>
                                </label>
                                <select id="village" name="village" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-rwandan-green focus:border-rwandan-green sm:text-sm">
                                    <option value="" data-translate="select_village">Hitamo Umudugudu</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="step-content hidden" id="step-2">
                    <div class="px-4 py-5 sm:p-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">
                            <span data-translate="institution_details">Urwego Bireba</span>
                        </h4>
                        <div class="space-y-6">
                            <div>
                                <label for="institution" class="block text-sm font-medium text-gray-700">
                                    <span data-translate="select_institution">Hitamo Urwego</span> *
                                </label>
                                <select id="institution" name="institution" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-rwandan-green focus:border-rwandan-green sm:text-sm">
                                    <option value="" data-translate="choose_institution">Hitamo Urwego</option>
                                    
                                </select>
                            </div>

                            <div id="other-institution-container" class="hidden">
                                <label for="other_institution" class="block text-sm font-medium text-gray-700">
                                    <span data-translate="specify_institution">Andika Urwego</span> *
                                </label>
                                <input type="text" name="other_institution" id="other_institution" class="mt-1 focus:ring-rwandan-green focus:border-rwandan-green block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <div>
                                <label for="service" class="block text-sm font-medium text-gray-700">
                                    <span data-translate="select_service">Hitamo Serivisi</span> *
                                </label>
                                <select id="service" name="service" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-rwandan-green focus:border-rwandan-green sm:text-sm">
                                    <option value="" data-translate="choose_service">Hitamo Serivisi</option>
                                </select>
                            </div>

                            <div id="other-service-container" class="hidden">
                                <label for="other_service" class="block text-sm font-medium text-gray-700">
                                    <span data-translate="specify_service">Andika Serivisi</span> *
                                </label>
                                <input type="text" name="other_service" id="other_service" class="mt-1 focus:ring-rwandan-green focus:border-rwandan-green block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox多元
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            <span data-translate="service_not_found">Niba udashobora kubona serivisi ukeneye, hitamo "Other" maze wandike serivisi ukeneye.</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="step-content hidden" id="step-3">
                    <div class="px-4 py-5 sm:p-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">
                            <span data-translate="problem_statement">Ikibazo Cyawe</span>
                        </h4>
                        <div class="space-y-6">
                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700">
                                    <span data-translate="subject">Inshamake y'Ikibazo</span> *
                                </label>
                                <input type="text" name="subject" id="subject" required class="mt-1 focus:ring-rwandan-green focus:border-rwandan-green block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">
                                    <span data-translate="description">Ibisobanuro Birambuye</span> *
                                </label>
                                <textarea id="description" name="description" rows="5" required class="mt-1 focus:ring-rwandan-green focus:border-rwandan-green block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                <p class="mt-2 text-sm text-gray-500">
                                    <span data-translate="description_help">Sobanura neza ikibazo cyawe. Tanga amakuru yose ashoboka kugira ngo ufashwe neza.</span>
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    <span data-translate="attachments">Inyandiko zifatika</span> <span class="text-xs text-gray-500">(<span data-translate="optional">Si ngombwa</span>)</span>
                                </label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-rwandan-blue hover:text-rwandan-green focus-within:outline-none">
                                                <span data-translate="upload_file">Shyiramo dosiye</span>
                                                <input id="file-upload" name="file-upload" type="file" class="sr-only" multiple>
                                            </label>
                                            <p class="pl-1"><span data-translate="or_drag">cyangwa ukurure hano</span></p>
                                        </div>
                                        <p class="text-xs text-gray-500">
                                            <span data-translate="file_types">PNG, JPG, PDF up to 10MB</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="step-content hidden" id="step-4">
                    <div class="px-4 py-5 sm:p-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">
                            <span data-translate="notification_preferences">Uburyo bwo Kukumenyesha</span>
                        </h4>
                        <p class="text-sm text-gray-500 mb-6">
                            <span data-translate="notification_description">Hitamo uburyo ushaka ko wakumenyesha igihe ikibazo cyawe gihindutse.</span>
                        </p>

                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="notify_sms" name="notify_sms" type="checkbox" class="focus:ring-rwandan-green h-4 w-4 text-rwandan-green border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="notify_sms" class="font-medium text-gray-700"><span data-translate="sms_notification">Ubutumwa bugufi (SMS)</span></label>
                                    <p class="text-gray-500"><span data-translate="sms_description">Ubutumwa bugufi buzajya bukugezaho amakuru ku kibazo cyawe.</span></p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="notify_email" name="notify_email" type="checkbox" class="focus:ring-rwandan-green h-4 w-4 text-rwandan-green border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="notify_email" class="font-medium text-gray-700"><span data-translate="email_notification">Imeri</span></label>
                                    <p class="text-gray-500"><span data-translate="email_description">Imeri izajya ikugezaho amakuru arambuye ku kibazo cyawe.</span></p>
                                </div>
                            </div>
                        </div>

                        
                        <div class="mt-8">
                            <h5 class="text-md font-medium text-gray-900 mb-3">
                                <span data-translate="language_preference">Ururimi Wifuza</span> *
                            </h5>
                            <p class="text-sm text-gray-500 mb-4">
                                <span data-translate="language_description">Hitamo ururimi ushaka ko twakumenyesha</span>
                            </p>

                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="lang_kinyarwanda" name="language" type="radio" value="kinyarwanda" checked class="focus:ring-rwandan-green h-4 w-4 text-rwandan-green border-gray-300">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="lang_kinyarwanda" class="font-medium text-gray-700">Kinyarwanda</label>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="lang_english" name="language" type="radio" value="english" class="focus:ring-rwandan-green h-4 w-4 text-rwandan-green border-gray-300">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="lang_english" class="font-medium text-gray-700">English</label>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="lang_french" name="language" type="radio" value="french" class="focus:ring-rwandan-green h-4 w-4 text-rwandan-green border-gray-300">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="lang_french" class="font-medium text-gray-700">Français</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 bg-blue-50 border-l-4 border-blue-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        <span data-translate="notification_info">Uzajya umenyeshwa igihe cyose ikibazo cyawe gihindutse. Ushobora guhitamo uburyo bumwe cyangwa bwombi.</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="step-content hidden" id="step-5">
                    <div class="px-4 py-5 sm:p-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">
                            <span data-translate="review_submit">Subiramo Mbere yo Kohereza</span>
                        </h4>
                        <p class="text-sm text-gray-500 mb-6">
                            <span data-translate="review_description">Nyamuneka, subiramo amakuru yawe mbere yo kohereza ikibazo.</span>
                        </p>

                        <div class="bg-gray-50 shadow overflow-hidden sm:rounded-lg">
                            <div class="px-4 py-5 sm:px-6">
                                <h5 class="text-lg leading-6 font-medium text-gray-900"><span data-translate="ticket_summary">Incamake y'Ikibazo</span></h5>
                                <p class="mt-1 max-w-2xl text-sm text-gray-500"><span data-translate="ticket_details">Amakuru y'ikibazo cyawe</span></p>
                            </div>
                            <div class="border-t border-gray-200">
                                <dl>
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500"><span data-translate="full_name">Amazina Yombi</span></dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2" id="review-full-name"></dd>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500"><span data-translate="contact_info">Aho Bakwandikira</span></dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            <div id="review-phone"></div>
                                            <div id="review-email"></div>
                                        </dd>
                                    </div>
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500"><span data-translate="location">Aho Utuye</span></dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2" id="review-location"></dd>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500"><span data-translate="institution">Urwego</span></dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2" id="review-institution"></dd>
                                    </div>
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500"><span data-translate="service">Serivisi</span></dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2" id="review-service"></dd>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500"><span data-translate="subject">Inshamake</span></dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2" id="review-subject"></dd>
                                    </div>
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500"><span data-translate="description">Ibisobanuro</span></dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2" id="review-description"></dd>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500"><span data-translate="notifications">Imenyesha</span></dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2" id="review-notifications"></dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <div class="mt-6">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="terms" name="terms" type="checkbox" required class="focus:ring-rwandan-green h-4 w-4 text-rwandan-green border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="terms" class="font-medium text-gray-700">
                                        <span data-translate="agree_terms">Nemeye ko amakuru natanze ari ukuri kandi yuzuye</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="next-btn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-rwandan-green text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rwandan-green sm:ml-3 sm:w-auto sm:text-sm">
                        <span data-translate="next">Komeza</span>
                    </button>
                    <button type="button" id="prev-btn" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rwandan-blue sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        <span data-translate="previous">Gusubira Inyuma</span>
                    </button>
                    <button type="submit" id="submit-btn" class="hidden w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-rwandan-green text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rwandan-green sm:ml-3 sm:w-auto sm:text-sm">
                        <span data-translate="submit">Ohereza</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div id="success-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            
            <div class="bg-rwandan-green px-4 py-3 sm:px-6 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-white">
                    <span data-translate="success_title">Ikibazo Cyawe Cyoherejwe!</span>
                </h3>
                <button type="button" class="close-success-modal text-white hover:text-gray-200">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            
            <div class="px-4 py-5 sm:p-6 text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                    <svg class="h-10 w-10 text-rwandan-green" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-2">
                    <span data-translate="thank_you">Murakoze!</span>
                </h3>
                <div class="mt-2">
                    <p class="text-sm text-gray-500 mb-4">
                        <span data-translate="success_message">Ikibazo cyawe cyoherejwe neza. Tuzakimenyesha vuba igihe cyose hari impinduka ku kibazo cyawe.</span>
                    </p>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-4">
                        <p class="text-sm font-medium text-gray-700 mb-1">
                            <span data-translate="ticket_number">Nomero y'Ikibazo:</span> <span id="ticket-number" class="font-bold text-rwandan-blue"></span>
                        </p>
                        <p class="text-xs text-gray-500">
                            <span data-translate="save_number">Bika iyi nomero kugira ngo uzakoreshe mu gukurikirana ikibazo cyawe.</span>
                        </p>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6">
                    <button type="button" class="close-success-modal inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-rwandan-green text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rwandan-green sm:text-sm">
                        <span data-translate="close">Funga</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // DOM Elements
        const ticketModal = document.getElementById('ticket-modal');
        const successModal = document.getElementById('success-modal');
        const ticketForm = document.getElementById('ticket-form');
        const nextBtn = document.getElementById('next-btn');
        const prevBtn = document.getElementById('prev-btn');
        const submitBtn = document.getElementById('submit-btn');
        const closeModalBtns = document.querySelectorAll('.close-modal');
        const closeSuccessModalBtns = document.querySelectorAll('.close-success-modal');
        const progressBar = document.querySelector('.progress-bar');

        // Open modal buttons
        const openModalBtns = document.querySelectorAll('.open-ticket-modal');

        // Step tracking
        let currentStep = 1;
        const totalSteps = 5;

        // Show the current step and update progress
        function showStep(step) {
            // Hide all steps
            document.querySelectorAll('.step-content').forEach(el => {
                el.classList.add('hidden');
            });

            // Show the current step
            document.getElementById(`step-${step}`).classList.remove('hidden');

            // Update progress bar
            const progress = (step / totalSteps) * 100;
            progressBar.style.width = `${progress}%`;

            // Update buttons
            prevBtn.classList.toggle('hidden', step === 1);
            nextBtn.classList.toggle('hidden', step === totalSteps);
            submitBtn.classList.toggle('hidden', step !== totalSteps);

            // Scroll to top of modal
            ticketModal.querySelector('.inline-block').scrollTop = 0;

            // Update current step
            currentStep = step;
        }

        // Next button click
        nextBtn.addEventListener('click', function() {
            // Validate current step
            const currentStepElement = document.getElementById(`step-${currentStep}`);
            const inputs = currentStepElement.querySelectorAll('input[required], select[required], textarea[required]');
            let isValid = true;

            inputs.forEach(input => {
                if (!input.checkValidity()) {
                    input.reportValidity();
                    isValid = false;
                }
            });

            if (isValid && currentStep < totalSteps) {
                showStep(currentStep + 1);

                // If moving to review step, populate review data
                if (currentStep === totalSteps) {
                    populateReviewData();
                }
            }
        });

        // Previous button click
        prevBtn.addEventListener('click', function() {
            if (currentStep > 1) {
                showStep(currentStep - 1);
            }
        });

        // Populate review data
        function populateReviewData() {
            // Personal info
            document.getElementById('review-full-name').textContent = document.getElementById('full_name').value;
            document.getElementById('review-phone').textContent = document.getElementById('phone').value;

            const email = document.getElementById('email').value;
            document.getElementById('review-email').textContent = email ? email : '(Not provided)';

            // Location
            const province = document.getElementById('province').options[document.getElementById('province').selectedIndex].text;
            const district = document.getElementById('district').options[document.getElementById('district').selectedIndex].text;
            const sector = document.getElementById('sector').options[document.getElementById('sector').selectedIndex].text;
            const cell = document.getElementById('cell').options[document.getElementById('cell').selectedIndex].text;
            const village = document.getElementById('village').options[document.getElementById('village').selectedIndex].text;

            document.getElementById('review-location').textContent = `${village}, ${cell}, ${sector}, ${district}, ${province}`;

            // Institution
            const institution = document.getElementById('institution').options[document.getElementById('institution').selectedIndex].text;
            document.getElementById('review-institution').textContent = institution === 'Other' ?
                document.getElementById('other_institution').value : institution;

            // Service
            const service = document.getElementById('service').options[document.getElementById('service').selectedIndex].text;
            document.getElementById('review-service').textContent = service === 'Other' ?
                document.getElementById('other_service').value : service;

            // Problem
            document.getElementById('review-subject').textContent = document.getElementById('subject').value;
            document.getElementById('review-description').textContent = document.getElementById('description').value;

            // Notifications
            const notifySms = document.getElementById('notify_sms').checked;
            const notifyEmail = document.getElementById('notify_email').checked;

            let notificationText = [];
            if (notifySms) notificationText.push('SMS');
            if (notifyEmail) notificationText.push('Email');

            // Get selected language
            let selectedLanguage = '';
            if (document.getElementById('lang_kinyarwanda').checked) selectedLanguage = 'Kinyarwanda';
            else if (document.getElementById('lang_english').checked) selectedLanguage = 'English';
            else if (document.getElementById('lang_french').checked) selectedLanguage = 'Français';

            document.getElementById('review-notifications').textContent = notificationText.length > 0 ?
                notificationText.join(', ') + ` (${selectedLanguage})` : 'None selected';
        }

        // Form submission
        ticketForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Collect all form data
            const formData = new FormData(ticketForm);

            // Add additional data that might not be captured by FormData
            formData.append('notify_sms', document.getElementById('notify_sms').checked ? '1' : '0');
            formData.append('notify_email', document.getElementById('notify_email').checked ? '1' : '0');

            // Get selected language
            let selectedLanguage = '';
            if (document.getElementById('lang_kinyarwanda').checked) selectedLanguage = 'kinyarwanda';
            else if (document.getElementById('lang_english').checked) selectedLanguage = 'english';
            else if (document.getElementById('lang_french').checked) selectedLanguage = 'french';
            formData.append('language', selectedLanguage);

            // Send data to server using fetch API
            fetch('save_ticket.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Set the ticket number in the success modal
                        document.getElementById('ticket-number').textContent = data.ticket_number;

                        // Hide ticket modal and show success modal
                        ticketModal.classList.add('hidden');
                        successModal.classList.remove('hidden');
                    } else {
                        // Show error message
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while submitting your ticket. Please try again.');
                });
        });

        // Open modal
        openModalBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                ticketModal.classList.remove('hidden');
                showStep(1);
            });
        });

        // Close modals
        closeModalBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                ticketModal.classList.add('hidden');
                resetForm();
            });
        });

        closeSuccessModalBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                successModal.classList.add('hidden');
                resetForm();
            });
        });

        // Reset form
        function resetForm() {
            ticketForm.reset();
            showStep(1);
        }
        loadInstitutions();
    
    // Handle institution selection
    const institutionSelect = document.getElementById('institution');
    const otherInstitutionContainer = document.getElementById('other-institution-container');

    institutionSelect.addEventListener('change', function() {
        if (this.value === 'other') {
            otherInstitutionContainer.classList.remove('hidden');
            document.getElementById('other_institution').setAttribute('required', 'required');
        } else {
            otherInstitutionContainer.classList.add('hidden');
            document.getElementById('other_institution').removeAttribute('required');
        }
        // Load services based on institution
        loadServices(this.value);
    });

    // Handle service selection
    const serviceSelect = document.getElementById('service');
    const otherServiceContainer = document.getElementById('other-service-container');

    serviceSelect.addEventListener('change', function() {
        if (this.value === 'other') {
            otherServiceContainer.classList.remove('hidden');
            document.getElementById('other_service').setAttribute('required', 'required');
        } else {
            otherServiceContainer.classList.add('hidden');
            document.getElementById('other_service').removeAttribute('required');
        }
    });

    function loadInstitutions() {
        fetch('fetch_data.php?action=get_institutions')
            .then(response => response.json())
            .then(data => {
                institutionSelect.innerHTML = '<option value="" data-translate="choose_institution">Hitamo Urwego</option>';
                data.forEach(institution => {
                    const option = document.createElement('option');
                    option.value = institution.id;
                    option.textContent = institution.name;
                    institutionSelect.appendChild(option);
                });
                // Add "Other" option
                institutionSelect.innerHTML += '<option value="other">Other</option>';
            })
            .catch(error => console.error('Error loading institutions:', error));
    }

    function loadServices(institutionId) {
        serviceSelect.innerHTML = '<option value="" data-translate="choose_service">Hitamo Serivisi</option>';
        
        if (institutionId && institutionId !== 'other') {
            // Get current language
            const currentLang = document.getElementById('current-language').textContent.trim().toLowerCase();
            const langMap = {
                'kinyarwanda': 'rw',
                'english': 'en',
                'french': 'fr'
            };
            const lang = langMap[currentLang] || 'rw';

            fetch(`fetch_data.php?action=get_services&institution_id=${institutionId}&lang=${lang}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(service => {
                        addServiceOption(service.id, service.name);
                    });
                    // Add "Other" option
                    serviceSelect.innerHTML += '<option value="other">Other</option>';
                })
                .catch(error => console.error('Error loading services:', error));
        } else {
            // Add "Other" option only
            serviceSelect.innerHTML += '<option value="other">Other</option>';
        }

        // Reset other service field
        otherServiceContainer.classList.add('hidden');
        document.getElementById('other_service').removeAttribute('required');
    }

    function addServiceOption(value, text) {
        const option = document.createElement('option');
        option.value = value;
        option.textContent = text;
        serviceSelect.appendChild(option);
    }

        // Load location data (provinces, districts, sectors, cells, villages)
        function loadLocationData() {
            // Fetch location data from JSON file
            fetch('locations.json')
                .then(response => response.json())
                .then(data => {
                    const provinceSelect = document.getElementById('province');

                    // Clear default option
                    provinceSelect.innerHTML = '<option value="" data-translate="select_province">Hitamo Intara</option>';

                    // Add provinces from JSON data
                    data.provinces.forEach(province => {
                        const option = document.createElement('option');
                        option.value = province.name.toLowerCase().replace(/\s+/g, '_');
                        option.textContent = province.name;
                        provinceSelect.appendChild(option);
                    });

                    // Store the full location data for later use
                    window.locationData = data;

                    // Handle province change
                    provinceSelect.addEventListener('change', function() {
                        loadDistricts(this.value);
                    });
                })
                .catch(error => console.error('Error loading location data:', error));
        }

        function loadDistricts(provinceName) {
            const districtSelect = document.getElementById('district');
            districtSelect.innerHTML = '<option value="" data-translate="select_district">Hitamo Akarere</option>';

            // Clear dependent dropdowns
            document.getElementById('sector').innerHTML = '<option value="" data-translate="select_sector">Hitamo Umurenge</option>';
            document.getElementById('cell').innerHTML = '<option value="" data-translate="select_cell">Hitamo Akagari</option>';
            document.getElementById('village').innerHTML = '<option value="" data-translate="select_village">Hitamo Umudugudu</option>';

            // Find the selected province in the data
            const province = window.locationData.provinces.find(p =>
                p.name.toLowerCase().replace(/\s+/g, '_') === provinceName
            );

            if (province && province.districts) {
                // Add districts from the selected province
                province.districts.forEach(district => {
                    const option = document.createElement('option');
                    option.value = district.name.toLowerCase().replace(/\s+/g, '_');
                    option.textContent = district.name;
                    districtSelect.appendChild(option);
                });
            }

            // Handle district change
            districtSelect.addEventListener('change', function() {
                loadSectors(this.value, provinceName);
            });
        }

        function loadSectors(districtName, provinceName) {
            const sectorSelect = document.getElementById('sector');
            sectorSelect.innerHTML = '<option value="" data-translate="select_sector">Hitamo Umurenge</option>';

            // Clear dependent dropdowns
            document.getElementById('cell').innerHTML = '<option value="" data-translate="select_cell">Hitamo Akagari</option>';
            document.getElementById('village').innerHTML = '<option value="" data-translate="select_village">Hitamo Umudugudu</option>';

            // Find the selected province and district in the data
            const province = window.locationData.provinces.find(p =>
                p.name.toLowerCase().replace(/\s+/g, '_') === provinceName
            );

            if (province) {
                const district = province.districts.find(d =>
                    d.name.toLowerCase().replace(/\s+/g, '_') === districtName
                );

                if (district && district.sectors) {
                    // Add sectors from the selected district
                    district.sectors.forEach(sector => {
                        const option = document.createElement('option');
                        option.value = sector.name.toLowerCase().replace(/\s+/g, '_');
                        option.textContent = sector.name;
                        sectorSelect.appendChild(option);
                    });
                }
            }

            // Handle sector change
            sectorSelect.addEventListener('change', function() {
                loadCells(this.value, districtName, provinceName);
            });
        }

        function loadCells(sectorName, districtName, provinceName) {
            const cellSelect = document.getElementById('cell');
            cellSelect.innerHTML = '<option value="" data-translate="select_cell">Hitamo Akagari</option>';

            // Clear dependent dropdown
            document.getElementById('village').innerHTML = '<option value="" data-translate="select_village">Hitamo Umudugudu</option>';

            // Find the selected province, district, and sector in the data
            const province = window.locationData.provinces.find(p =>
                p.name.toLowerCase().replace(/\s+/g, '_') === provinceName
            );

            if (province) {
                const district = province.districts.find(d =>
                    d.name.toLowerCase().replace(/\s+/g, '_') === districtName
                );

                if (district) {
                    const sector = district.sectors.find(s =>
                        s.name.toLowerCase().replace(/\s+/g, '_') === sectorName
                    );

                    if (sector && sector.cells) {
                        // Add cells from the selected sector
                        sector.cells.forEach(cell => {
                            const option = document.createElement('option');
                            option.value = cell.name.toLowerCase().replace(/\s+/g, '_');
                            option.textContent = cell.name;
                            cellSelect.appendChild(option);
                        });
                    }
                }
            }

            // Handle cell change
            cellSelect.addEventListener('change', function() {
                loadVillages(this.value, sectorName, districtName, provinceName);
            });
        }

        function loadVillages(cellName, sectorName, districtName, provinceName) {
            const villageSelect = document.getElementById('village');
            villageSelect.innerHTML = '<option value="" data-translate="select_village">Hitamo Umudugudu</option>';

            // Find the selected province, district, sector, and cell in the data
            const province = window.locationData.provinces.find(p =>
                p.name.toLowerCase().replace(/\s+/g, '_') === provinceName
            );

            if (province) {
                const district = province.districts.find(d =>
                    d.name.toLowerCase().replace(/\s+/g, '_') === districtName
                );

                if (district) {
                    const sector = district.sectors.find(s =>
                        s.name.toLowerCase().replace(/\s+/g, '_') === sectorName
                    );

                    if (sector) {
                        const cell = sector.cells.find(c =>
                            c.name.toLowerCase().replace(/\s+/g, '_') === cellName
                        );

                        if (cell && cell.villages) {
                            // Add villages from the selected cell
                            cell.villages.forEach(village => {
                                const option = document.createElement('option');
                                option.value = village.name.toLowerCase().replace(/\s+/g, '_');
                                option.textContent = village.name;
                                villageSelect.appendChild(option);
                            });
                        }
                    }
                }
            }
        }

        // Initialize location data
        loadLocationData();

        // Initialize email notification checkbox based on whether email is provided
        document.getElementById('email').addEventListener('input', function() {
            const notifyEmail = document.getElementById('notify_email');
            if (this.value) {
                notifyEmail.disabled = false;
            } else {
                notifyEmail.checked = false;
                notifyEmail.disabled = true;
            }
        });

        // Trigger email input event on page load
        document.getElementById('email').dispatchEvent(new Event('input'));
    });
</script>