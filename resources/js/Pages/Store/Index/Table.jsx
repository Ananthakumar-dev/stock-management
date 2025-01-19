import NavLink from "@/Components/NavLink";
import { decodeHtmlEntities } from "@/utils/helpers";
import { useForm } from "@inertiajs/react";
import React from "react";
import Filter from "./Filter";

const Table = ({ stores, initialSearch }) => {
    const { delete: destroy } = useForm({});

    const handleDelete = (storeId) => {
        if (!storeId) return false;

        const confimation = confirm(
            "Are you sure want to delete? This process is irreversible."
        );
        if (confimation) {
            destroy(route("stores.delete", storeId));
        }
    };

    return (
        <>
            {/* Top section: Add button and search box */}
            <div className="text-right">
                <NavLink href={route("stores.create")}>Add Store</NavLink>
            </div>

            <div>
                <Filter initialSearch={initialSearch} />
            </div>

            {/* No results found */}
            {!stores.total && <h1 className="text-center">No stores found!</h1>}

            {/* Table Section */}
            {stores.total > 0 && (
                <>
                    <div className="overflow-x-auto mt-1">
                        <table className="min-w-full border-collapse border border-gray-300">
                            <thead>
                                <tr className="bg-gray-200">
                                    <th className="border border-gray-300 px-4 py-2 text-left">
                                        Id
                                    </th>
                                    <th className="border border-gray-300 px-4 py-2 text-left">
                                        Name
                                    </th>
                                    <th className="border border-gray-300 px-4 py-2 text-left">
                                        Phone
                                    </th>
                                    <th className="border border-gray-300 px-4 py-2 text-center">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {/* Example rows */}
                                {stores.data.map((store) => (
                                    <tr
                                        key={store.id}
                                        className="odd:bg-white even:bg-gray-100"
                                    >
                                        <td className="border border-gray-300 px-4 py-2">
                                            {store.id}
                                        </td>
                                        <td className="border border-gray-300 px-4 py-2">
                                            {store.name}
                                        </td>
                                        <td className="border border-gray-300 px-4 py-2">
                                            {store.phone || "N/A"}
                                        </td>
                                        <td className="border border-gray-300 px-4 py-2 text-center">
                                            <NavLink
                                                href={`/stores/show/${store.id}`}
                                            >
                                                <button className="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 mr-2">
                                                    Edit
                                                </button>
                                            </NavLink>

                                            <button
                                                onClick={() =>
                                                    handleDelete(store.id)
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
                        {stores.links.map((link, index) => (
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
