import NavLink from "@/Components/NavLink";
import { useForm } from "@inertiajs/react";
import React from "react";
import { decodeHtmlEntities } from "@/utils/helpers";
import TextInput from "@/Components/TextInput";
import PrimaryButton from "@/Components/PrimaryButton";
import Filter from "./Filter";

const Table = ({ users, initialSearch }) => {
    const { delete: destroy, get } = useForm({});

    const handleDelete = (userId) => {
        if (!userId) return false;

        const confimation = confirm(
            "Are you sure want to delete? This process is irreversible."
        );
        if (confimation) {
            destroy(route("users.delete", userId));
        }
    };

    return (
        <>
            {/* Top section: Add button and search box */}
            <div className="text-right">
                <NavLink href={route("users.create")}>Add User</NavLink>
            </div>

            <div>
                <Filter initialSearch={initialSearch} />
            </div>

            {/* No results found */}
            {!users.total && <h1 className="text-center">No users found!</h1>}

            {/* Table Section */}
            {users.total > 0 && (
                <>
                    <div className="overflow-x-auto mt-2">
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
                                        Designation
                                    </th>
                                    <th className="border border-gray-300 px-4 py-2 text-left">
                                        Email
                                    </th>
                                    <th className="border border-gray-300 px-4 py-2 text-left">
                                        Phone
                                    </th>
                                    <th className="border border-gray-300 px-4 py-2 text-left">
                                        Status
                                    </th>
                                    <th className="border border-gray-300 px-4 py-2 text-center">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {/* Example rows */}
                                {users.data.map((user) => (
                                    <tr
                                        key={user.id}
                                        className="odd:bg-white even:bg-gray-100"
                                    >
                                        <td className="border border-gray-300 px-4 py-2">
                                            {user.id}
                                        </td>
                                        <td className="border border-gray-300 px-4 py-2">
                                            {user.name}
                                        </td>
                                        <td className="border border-gray-300 px-4 py-2">
                                            {user.designation}
                                        </td>
                                        <td className="border border-gray-300 px-4 py-2">
                                            {user.email || "N/A"}
                                        </td>
                                        <td className="border border-gray-300 px-4 py-2">
                                            {user.phone || "N/A"}
                                        </td>
                                        <td className="border border-gray-300 px-4 py-2">
                                            {user.status}
                                        </td>
                                        <td className="border border-gray-300 px-4 py-2 text-center">
                                            <NavLink
                                                href={`/users/show/${user.id}`}
                                            >
                                                <button className="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 mr-2">
                                                    Edit
                                                </button>
                                            </NavLink>

                                            <button
                                                onClick={() =>
                                                    handleDelete(user.id)
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
                        {users.links.map((link, index) => (
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
