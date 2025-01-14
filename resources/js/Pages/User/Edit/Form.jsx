import React from "react";
import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import TextInput from "@/Components/TextInput";
import { Transition } from '@headlessui/react';
import { Link, useForm, usePage } from '@inertiajs/react';
import PrimaryButton from "@/Components/PrimaryButton";
import SelectDropdown from "@/Components/SelectDropdown";

const Form = ({ statuses, user }) => {
    const { data, setData, patch, errors, processing, recentlySuccessful } =
        useForm({
            name: user.name,
            email: user.email,
            phone: user.phone,
            designation: user.designation,
            status: user.status
        });

    const submit = (e) => {
        e.preventDefault();

        patch(route('users.update', user.id));
    };

    return (
        <section>
            <form onSubmit={submit} className="mt-6 space-y-6">
                <div>
                    <InputLabel htmlFor="name" value="Name" />

                    <TextInput
                        id="name"
                        className="mt-1 block w-full"
                        value={data.name}
                        onChange={(e) => setData("name", e.target.value)}
                        required
                        isFocused
                        autoComplete="name"
                    />

                    <InputError className="mt-2" message={errors.name} />
                </div>

                <div>
                    <InputLabel htmlFor="email" value="Email" />

                    <TextInput
                        id="email"
                        type="email"
                        className="mt-1 block w-full"
                        value={data.email}
                        onChange={(e) => setData("email", e.target.value)}
                        required
                        autoComplete="username"
                    />

                    <InputError className="mt-2" message={errors.email} />
                </div>

                <div>
                    <InputLabel htmlFor="phone" value="phone" />

                    <TextInput
                        id="phone"
                        type="tel"
                        className="mt-1 block w-full"
                        value={data.phone}
                        onChange={(e) => setData("phone", e.target.value)}
                        required
                        autoComplete="username"
                    />

                    <InputError className="mt-2" message={errors.phone} />
                </div>

                <div>
                    <InputLabel htmlFor="designation" value="designation" />

                    <TextInput
                        id="designation"
                        type="text"
                        className="mt-1 block w-full"
                        value={data.designation}
                        onChange={(e) => setData("designation", e.target.value)}
                        required
                        autoComplete="username"
                    />

                    <InputError className="mt-2" message={errors.designation} />
                </div>

                <div>
                    <InputLabel htmlFor="status" value="status" />

                    <SelectDropdown 
                        id="status"
                        name="status"
                        value={data.status}
                        className="mt-1 block w-full"
                        onChange={(e) => setData("status", e.target.value)}
                        options={statuses}
                        required
                    />

                    <InputError className="mt-2" message={errors.status} />
                </div>

                <div className="flex items-center gap-4">
                    <PrimaryButton disabled={processing}>Save</PrimaryButton>

                    <Transition
                        show={recentlySuccessful}
                        enter="transition ease-in-out"
                        enterFrom="opacity-0"
                        leave="transition ease-in-out"
                        leaveTo="opacity-0"
                    >
                        <p className="text-sm text-gray-600 dark:text-gray-400">
                            Saved.
                        </p>
                    </Transition>
                </div>
            </form>
        </section>
    );
};

export default Form;
