import NavLink from "@/Components/NavLink";
import { useForm } from "@inertiajs/react";
import React from "react";
import Filter from "./Filter";
import { decodeHtmlEntities } from "@/utils/helpers";

const Table = ({ inventories, initialSearch }) => {
    const { delete: destroy } = useForm({});

    const handleDelete = (inventoryId) => {
        if (!inventoryId) return false;

        const confimation = confirm(
            "Are you sure want to delete? This process is irreversible."
        );
        if (confimation) {
            destroy(route("inventories.delete", inventoryId));
        }
    };

    return (
        <>
            {/* Top section: Add button and search box */}
            <div className="text-right">
                <NavLink href={route("inventories.create")}>
                    Add inventory
                </NavLink>
            </div>

            <div>
                <Filter initialSearch={initialSearch} />
            </div>

            {/* No results found */}
            {!inventories.total && (
                <h1 className="text-center">No inventories found!</h1>
            )}

            {/* Table Section */}
            {inventories.total > 0 && (
                <>
                    <div className="overflow-x-auto mt-1">
                        <table className="min-w-full border-collapse border border-gray-300">
                            <thead>
                                <tr className="bg-gray-200">
                                    <th className="border border-gray-300 px-4 py-2 text-left">
                                        Id
                                    </th>
                                    <th className="border border-gray-300 px-4 py-2 text-left">
                                        Item Name
                                    </th>
                                    <th className="border border-gray-300 px-4 py-2 text-left">
                                        Store Name
                                    </th>
                                    <th className="border border-gray-300 px-4 py-2 text-left">
                                        User Name
                                    </th>
                                    <th className="border border-gray-300 px-4 py-2 text-left">
                                        Quantity
                                    </th>
                                    <th className="border border-gray-300 px-4 py-2 text-left">
                                        Type
                                    </th>
                                    <th className="border border-gray-300 px-4 py-2 text-center">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {/* Example rows */}
                                {inventories.data.map((inventory) => (
                                    <tr
                                        key={inventory.id}
                                        className="odd:bg-white even:bg-gray-100"
                                    >
                                        <td className="border border-gray-300 px-4 py-2">
                                            {inventory.id}
                                        </td>
                                        <td className="border border-gray-300 px-4 py-2">
                                            {inventory.item_name}
                                        </td>
                                        <td className="border border-gray-300 px-4 py-2">
                                            {inventory.store_name}
                                        </td>
                                        <td className="border border-gray-300 px-4 py-2">
                                            {inventory.user_name}
                                        </td>
                                        <td className="border border-gray-300 px-4 py-2">
                                            {inventory.quantity}
                                        </td>
                                        <td className="border border-gray-300 px-4 py-2">
                                            {inventory.type}
                                        </td>
                                        <td className="border border-gray-300 px-4 py-2 text-center">
                                            <NavLink
                                                href={`/inventories/show/${inventory.id}`}
                                            >
                                                <button className="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 mr-2">
                                                    Edit
                                                </button>
                                            </NavLink>

                                            <button
                                                onClick={() =>
                                                    handleDelete(inventory.id)
                                                }
                                                className="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600"
                                            >
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>

                    {/* Pagination Section */}
                    <div className="mt-4 flex justify-center">
                        {inventories.links.map((link, index) => (
                            <NavLink
                                key={index}
                                href={link.url}
                                active={link.active}
                                className={
                                    !link.url
                                        ? "pointer-events-none cursor-not-allowed opacity-50"
                                        : ""
                                }
                            >
                                {decodeHtmlEntities(link.label)}
                            </NavLink>
                        ))}
                    </div>
                </>
            )}
        </>
    );
};

export default Table;
