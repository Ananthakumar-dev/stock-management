import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import React, { useState, useEffect } from "react";
import axios from "axios";
import { router } from "@inertiajs/react";

const inventoryBasicDataUrl = route("inventories.basicData");
const inventoryIndexUrl = route("inventories.index");

const Form = ({ inventory }) => {
    const [data, setData] = useState({
        users: [],
        stores: [],
        items: [],
    });
    const [formData, setFormData] = useState({
        user_id: inventory.user_id || "",
        store_id: inventory.store_id || "",
        item_id: inventory.item_id || "",
        type: inventory.type || "In",
        quantity: inventory.quantity || "",
    });
    const [showAttributes, setShowAttributes] = useState(false);
    const [errors, setErrors] = useState({});

    const itemDetails = inventory.item;

    useEffect(() => {
        // Fetch all dropdown data in one API call
        const fetchData = async () => {
            try {
                const response = await axios.get(inventoryBasicDataUrl);
                setData(response.data); // Set users, stores, items
            } catch (error) {
                console.error("Error fetching data", error);
            }
        };

        fetchData();
    }, []);

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData({ ...formData, [name]: value });

        // Update attributes and measurement on item change
        if (name === "item_id") {
            const selectedItem = data.items.find(
                (item) => item.id === parseInt(value)
            );
            if (selectedItem) {
                setItemAttributes(selectedItem.attributes || []);
                setItemMeasurement(selectedItem.measurement?.name || "");
            }
        }
    };

    const handleSubmit = async (e) => {
        e.preventDefault();

        const inventoryUpdateUrl = route("inventories.update", inventory.id);

        try {
            await axios.post(inventoryUpdateUrl, formData);

            router.visit(inventoryIndexUrl);
        } catch (error) {
            if (error.response && error.response.data.errors) {
                setErrors(error.response.data.errors);
            }
        }
    };

    return (
        <>
            <form onSubmit={handleSubmit} className="space-y-6">
                {/* User Dropdown */}
                <div className="space-y-2">
                    <InputLabel>User</InputLabel>

                    <select
                        name="user_id"
                        value={formData.user_id}
                        onChange={handleChange}
                        className={`border ${
                            errors.user_id
                                ? "border-red-500"
                                : "border-gray-300 dark:border-gray-700"
                        } bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-200 rounded px-4 py-2 w-full`}
                        required
                    >
                        <option value="">Select</option>
                        {data.users.map((user) => (
                            <option key={user.id} value={user.id}>
                                {user.name}
                            </option>
                        ))}
                    </select>
                    {errors.user_id && (
                        <InputError>{errors.user_id[0]}</InputError>
                    )}
                </div>

                {/* Store Dropdown */}
                <div className="space-y-2">
                    <InputLabel>Store</InputLabel>

                    <select
                        name="store_id"
                        value={formData.store_id}
                        onChange={handleChange}
                        className={`border ${
                            errors.store_id
                                ? "border-red-500"
                                : "border-gray-300 dark:border-gray-700"
                        } bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-200 rounded px-4 py-2 w-full`}
                        required
                    >
                        <option value="">Select</option>
                        {data.stores.map((store) => (
                            <option key={store.id} value={store.id}>
                                {store.name}
                            </option>
                        ))}
                    </select>
                    {errors.store_id && (
                        <InputError>{errors.store_id[0]}</InputError>
                    )}
                </div>

                {/* Item Dropdown */}
                <div className="space-y-2">
                    <InputLabel>Item</InputLabel>

                    <select
                        name="item_id"
                        value={formData.item_id}
                        onChange={handleChange}
                        className={`border ${
                            errors.item_id
                                ? "border-red-500"
                                : "border-gray-300 dark:border-gray-700"
                        } bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-200 rounded px-4 py-2 w-full`}
                        required
                    >
                        <option value="">Select</option>
                        {data.items.map((item) => (
                            <option key={item.id} value={item.id}>
                                {item.name}
                            </option>
                        ))}
                    </select>
                    {errors.item_id && (
                        <InputError>{errors.item_id[0]}</InputError>
                    )}
                </div>

                {/* Display Item Details */}
                {itemDetails && (
                    <div className="space-y-1">
                        <p className="text-sm text-gray-700 dark:text-gray-300">
                            <strong>Measurement:</strong>{" "}
                            {itemDetails.measurement.name || "N/A"}
                        </p>
                        <p className="text-sm text-gray-700 dark:text-gray-300">
                            <strong>Attributes:</strong>{" "}
                            <a
                                href="javascript:;"
                                onClick={() =>
                                    setShowAttributes(!showAttributes)
                                }
                            >
                                Show
                            </a>
                        </p>
                    </div>
                )}

                {/* Inventory Type Dropdown */}
                <div className="space-y-2">
                    <InputLabel>Inventory Type</InputLabel>

                    <select
                        name="type"
                        value={formData.type}
                        onChange={handleChange}
                        className="border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-200 rounded px-4 py-2 w-full"
                        required
                    >
                        <option value="In">In</option>
                        <option value="Out">Out</option>
                    </select>
                </div>

                {/* Quantity */}
                <div className="space-y-2">
                    <InputLabel>Quantity</InputLabel>

                    <input
                        name="quantity"
                        type="number"
                        value={formData.quantity}
                        onChange={handleChange}
                        className={`border ${
                            errors.quantity
                                ? "border-red-500"
                                : "border-gray-300 dark:border-gray-700"
                        } bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-200 rounded px-4 py-2 w-full`}
                    />
                    {errors.quantity && (
                        <InputError>{errors.quantity[0]}</InputError>
                    )}
                </div>

                {/* Submit Button */}
                <button
                    type="submit"
                    className="px-6 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 dark:hover:bg-blue-400"
                >
                    Submit
                </button>
            </form>

            {/* Modal for attributes */}
            {showAttributes && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div className="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-lg w-full p-6">
                        <h2 className="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            Item Attributes
                        </h2>
                        {itemDetails.item_attributes ? (
                            <ul className="space-y-2">
                                {itemDetails.item_attributes.map(
                                    (attr, index) => (
                                        <li
                                            key={index}
                                            className="text-gray-700 dark:text-gray-300 flex justify-between"
                                        >
                                            <span>{attr.attribute?.name}</span>
                                            <span>{attr.value}</span>
                                        </li>
                                    )
                                )}
                            </ul>
                        ) : (
                            <p className="text-gray-700 dark:text-gray-300">
                                No attributes available for this item.
                            </p>
                        )}
                        <div className="mt-6 flex justify-end">
                            <button
                                onClick={() => setShowAttributes(false)}
                                className="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded hover:bg-gray-400 dark:hover:bg-gray-600"
                            >
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </>
    );
};

export default Form;
