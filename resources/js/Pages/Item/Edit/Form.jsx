import React from "react";
import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import TextInput from "@/Components/TextInput";
import { Transition } from "@headlessui/react";
import { Link, useForm, usePage } from "@inertiajs/react";
import PrimaryButton from "@/Components/PrimaryButton";
import SelectDropdown from "@/Components/SelectDropdown";
import { useState } from "react";
import Attributes from "./Attributes";
import { router } from "@inertiajs/react";

const Form = ({ item, measurements }) => {
    const [errors, setErrors] = useState({}); // Validation errors

    const submit = async (e) => {
        e.preventDefault();
        setErrors({});

        const data = new FormData(e.target);
        const url = route("items.update");

        try {
            const response = await axios.post(url, data);
            router.visit(indexUrl);
        } catch (error) {
            if (error.response && error.response.data.errors) {
                setErrors(error.response.data.errors); // Capture validation errors
            }
        }
    };

    return (
        <section>
            <form onSubmit={submit} className="mt-6 space-y-6">
                <div>
                    <InputLabel htmlFor="name" value="Name" />

                    <TextInput
                        id="name"
                        name="name"
                        defaultValue={item.name}
                        className="mt-1 block w-full"
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
                        defaultValue={item.description}
                        required
                    />

                    <InputError className="mt-2" message={errors.description} />
                </div>

                <div className="flex gap-1">
                    <div>
                        <InputLabel htmlFor="quantity" value="quantity" />

                        <TextInput
                            id="quantity"
                            name="quantity"
                            type="number"
                            className="mt-1 flex-grow"
                            required
                            autoComplete="username"
                            defaultValue={item.quantity}
                        />

                        <InputError
                            className="mt-2"
                            message={errors.quantity}
                        />
                    </div>

                    <div>
                        <InputLabel htmlFor="measurement" value="Measurement" />

                        <select
                            className="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600 mt-1"
                            name="measurement_id"
                            defaultValue={item.measurement_id}
                            required
                        >
                            <option value="">Select</option>
                            {measurements.map((measurement) => (
                                <option
                                    key={measurement.id}
                                    value={measurement.id}
                                >
                                    {measurement.name}
                                </option>
                            ))}
                        </select>

                        <InputError
                            className="mt-2"
                            message={errors.measurement_id}
                        />
                    </div>
                </div>

                <Attributes
                    errors={errors}
                    setErrors={setErrors}
                    itemAttributes={item.item_attributes}
                />

                <div className="flex items-center gap-4">
                    <PrimaryButton>Save</PrimaryButton>
                </div>
            </form>
        </section>
    );
};

export default Form;
