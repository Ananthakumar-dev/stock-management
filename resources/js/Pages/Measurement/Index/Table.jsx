import NavLink from "@/Components/NavLink";
import { decodeHtmlEntities } from "@/utils/helpers";
import { useForm } from "@inertiajs/react";
import React from "react";

const Table = ({ measurements }) => {
    const { delete: destroy } = useForm({});

    const handleDelete = (measurementId) => {
        if (!measurementId) return false;

        const confimation = confirm(
            "Are you sure want to delete? This process is irreversible."
        );
        if (confimation) {
            destroy(route("measurements.delete", measurementId));
        }
    };

    return (
        <>
            {/* Top section: Add button and search box */}
            <div className="text-right">
                <NavLink href={route("measurements.create")}>
                    Add Measurement
                </NavLink>
            </div>

            {/* No results found */}
            {!measurements.total && (
                <h1 className="text-center">No measurements found!</h1>
            )}

            {/* Table Section */}
            {measurements.total > 0 && (
                <>
                    <input
                        type="text"
                        placeholder="Search..."
                        className="border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300"
                    />

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
                                    <th className="border border-gray-300 px-4 py-2 text-center">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {/* Example rows */}
                                {measurements.data.map((measurement) => (
                                    <tr
                                        key={measurement.id}
                                        className="odd:bg-white even:bg-gray-100"
                                    >
                                        <td className="border border-gray-300 px-4 py-2">
                                            {measurement.id}
                                        </td>
                                        <td className="border border-gray-300 px-4 py-2">
                                            {measurement.name}
                                        </td>
                                        <td className="border border-gray-300 px-4 py-2 text-center">
                                            <button className="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 mr-2">
                                                <NavLink
                                                    href={`/measurements/show/${measurement.id}`}
                                                >
                                                    Edit
                                                </NavLink>
                                            </button>

                                            <button
                                                onClick={() =>
                                                    handleDelete(measurement.id)
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
                        {measurements.links.map((link, index) => (
                            <NavLink
                                key={index}
                                href={link.url}
                                active={link.active}
                                className={
                                    !link.active
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
