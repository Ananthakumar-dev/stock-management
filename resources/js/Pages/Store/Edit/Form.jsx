import React from "react";
import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import TextInput from "@/Components/TextInput";
import { Transition } from "@headlessui/react";
import { Link, useForm, usePage } from "@inertiajs/react";
import PrimaryButton from "@/Components/PrimaryButton";
import SelectDropdown from "@/Components/SelectDropdown";

const Form = ({ store }) => {
    const { data, setData, patch, errors, processing, recentlySuccessful } =
        useForm({
            name: store.name,
            description: store.description,
            phone: store.phone,
            block: store.address?.block,
            street: store.address?.street,
            city: store.address?.city,
        });

    const submit = (e) => {
        e.preventDefault();

        patch(route("stores.update", store.id));
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
                    <InputLabel htmlFor="description" value="Description" />

                    <TextInput
                        id="description"
                        type="description"
                        className="mt-1 block w-full"
                        value={data.description}
                        onChange={(e) => setData("description", e.target.value)}
                        required
                    />

                    <InputError className="mt-2" message={errors.description} />
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
                    />

                    <InputError className="mt-2" message={errors.phone} />
                </div>

                <div className="flex gap-1">
                    <div className="flex-grow">
                        <InputLabel htmlFor="block" value="Block" />

                        <TextInput
                            id="block"
                            type="number"
                            className="mt-1 w-full"
                            value={data.block}
                            onChange={(e) => setData("block", e.target.value)}
                            required
                        />

                        <InputError className="mt-2" message={errors.block} />
                    </div>

                    <div className="flex-grow">
                        <InputLabel htmlFor="street" value="Street" />

                        <TextInput
                            id="street"
                            type="text"
                            className="mt-1 w-full"
                            value={data.street}
                            onChange={(e) => setData("street", e.target.value)}
                            required
                        />

                        <InputError className="mt-2" message={errors.street} />
                    </div>

                    <div className="flex-grow">
                        <InputLabel htmlFor="city" value="City" />

                        <TextInput
                            id="city"
                            type="text"
                            className="mt-1 w-full"
                            value={data.city}
                            onChange={(e) => setData("city", e.target.value)}
                            required
                        />

                        <InputError className="mt-2" message={errors.city} />
                    </div>
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
